<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditConfiguration;

class EdgarEzAuditConfigurationRepository extends EntityRepository
{
    public function save(EdgarEzAuditConfiguration $auditConfiguration)
    {
        $this->getEntityManager()->persist($auditConfiguration);
        $this->getEntityManager()->flush();
    }
}
