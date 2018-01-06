<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIAudit\Form\Data\AuditData;
use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditLog;

class EdgarEzAuditLogRepository extends EntityRepository
{
    public function log(
        int $userId,
        string $groupName,
        string $auditIdentifier,
        string $auditName,
        array $infos
    ) {
        $auditLog = new EdgarEzAuditLog();
        $auditLog->setUserId($userId);
        $auditLog->setGroupName($groupName);
        $auditLog->setAuditIdentifier($auditIdentifier);
        $auditLog->setAuditName($auditName);
        $auditLog->setInfos($infos);
        $auditLog->setDate(new \DateTime);

        $this->getEntityManager()->persist($auditLog);
        $this->getEntityManager()->flush();
    }

    public function buildQuery(FilterAuditData $data): QueryBuilder
    {
        $entityManager = $this->getEntityManager();

        if ($data->getAuditTypes() && count($data->getAuditTypes())) {
            $auditIdentifiers = [];
            /** @var AuditData[] $auditTypes */
            $auditTypes = $data->getAuditTypes();
            foreach ($auditTypes as $auditType) {
                $auditIdentifiers[] = $auditType->getIdentifier();
            }
            $qbFilterAudit = $entityManager->createQueryBuilder();
            $qbFilterAudit->expr()->in('l.audit_identifier', ':audit_identifiers');
            $qbFilterAudit->setParameter('audit_identifiers', $auditIdentifiers);
        }

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('l')
            ->from(EdgarEzAuditLog::class, 'l')
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->in('l.auditIdentifier', ':audit_identifiers'),
                $queryBuilder->expr()->gte('l.date', ':date_start'),
                $queryBuilder->expr()->lte('l.date', ':date_end')
            ))
            ->orderBy('l.date', 'DESC')
            ->setParameter('audit_identifiers', $auditIdentifiers)
            ->setParameter('date_start', $data->getDateStart())
            ->setParameter('date_end', $data->getDateEnd());

        return $queryBuilder;
    }
}
