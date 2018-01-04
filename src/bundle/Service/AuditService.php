<?php

namespace Edgar\EzUIAuditBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Edgar\EzUIAudit\Audit\AuditInterface;
use Edgar\EzUIAudit\Form\Data\AuditData;
use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Handler\AuditHandler;
use Edgar\EzUIAudit\Repository\EdgarEzAuditConfigurationRepository;
use Edgar\EzUIAudit\Repository\EdgarEzAuditLogRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditConfiguration;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditLog;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AuditService
{
    /** @var AuditHandler  */
    protected $auditHandler;

    /** @var TokenStorage  */
    protected $tokenStorage;

    /** @var EdgarEzAuditConfigurationRepository  */
    protected $auditConfiguration;

    /** @var EdgarEzAuditLogRepository  */
    protected $auditLog;

    public function __construct(
        AuditHandler $auditHandler,
        Registry $doctrineRegistry,
        TokenStorage $tokenStorage
    ) {
        $this->auditHandler = $auditHandler;
        $this->tokenStorage = $tokenStorage;
        $entityManager = $doctrineRegistry->getManager();
        $this->auditConfiguration = $entityManager->getRepository(EdgarEzAuditConfiguration::class);
        $this->auditLog = $entityManager->getRepository(EdgarEzAuditLog::class);
    }

    public function loadAuditTypeGroups(): array
    {
        $audits = $this->auditHandler->getAudits();
        $auditGroups = array_keys($audits);
        ksort($auditGroups);
        return $auditGroups;
    }

    public function loadAuditTypes(string $auditTypeGroup): array
    {
        $audits = $this->auditHandler->getAudits();

        $return = [];
        foreach ($audits[$auditTypeGroup] as $audit) {
            $classInfos = explode('\\', get_class($audit));
            $classIdentifier = $classInfos[count($classInfos) - 1];
            $className = str_replace('Audit', '', $classIdentifier);

            $auditData = new AuditData();
            $auditData->setIdentifier($classIdentifier);
            $auditData->setName(preg_replace('/(?<!^)([A-Z])/', ' \\1', $className));
            $return[] = $auditData;
        }

        return $return;
    }

    public function getAuditConfiguration(): ConfigureAuditData
    {
        /** @var EdgarEzAuditConfiguration[] $auditConfigurations */
        $auditConfigurations = $this->auditConfiguration->findAll();

        $configureAuditData = new ConfigureAuditData();

        if ($auditConfigurations && count($auditConfigurations)) {
            $configureAuditData->setAuditTypes($auditConfigurations[0]->getAudits());
        }

        return $configureAuditData;
    }

    public function saveAuditConfiguration(array $audits)
    {
        /** @var EdgarEzAuditConfiguration[] $auditConfigurations */
        $auditConfigurations = $this->auditConfiguration->findAll();

        $auditConfiguration = new EdgarEzAuditConfiguration();
        if ($auditConfigurations && count($auditConfigurations)) {
            $auditConfiguration = $auditConfigurations[0];
        }
        $auditConfiguration->setAudits($audits);

        $this->auditConfiguration->save($auditConfiguration);
    }

    public function isConfigured(string $classPath): bool
    {
        $auditConfiguration = $this->getAuditConfiguration();
        /** @var AuditData[] $audits */
        $audits = $auditConfiguration->getAuditTypes();

        $classInfos = explode('\\', $classPath);
        $className = $classInfos[count($classInfos) - 1];
        foreach ($audits as $audit) {
            if ($audit->getIdentifier() == $className) {
                return true;
            }
        }

        return false;
    }

    public function log(AuditInterface $audit)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $apiUser = $user->getAPIUser();

        $this->auditLog->log($apiUser->id, $audit->getGroup(), $audit->getName(), $audit->getInfos());
    }
}
