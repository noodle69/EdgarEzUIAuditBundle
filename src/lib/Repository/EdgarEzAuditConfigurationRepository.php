<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditConfiguration;

class EdgarEzAuditConfigurationRepository extends EntityRepository
{
    /**
     * Save Audit.
     *
     * @param EdgarEzAuditConfiguration $auditConfiguration
     *
     * @throws ORMException
     */
    public function save(EdgarEzAuditConfiguration $auditConfiguration)
    {
        try {
            $this->getEntityManager()->persist($auditConfiguration);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw $e;
        }
    }
}
