<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditLog;

class EdgarEzAuditLogRepository extends EntityRepository
{
    public function log(
        int $userId,
        string $groupName,
        string $auditName,
        array $infos
    ) {
        $auditLog = new EdgarEzAuditLog();
        $auditLog->setUserId($userId);
        $auditLog->setGroupName($groupName);
        $auditLog->setAuditName($auditName);
        $auditLog->setInfos($infos);
        $auditLog->setDate(new \DateTime);

        $this->getEntityManager()->persist($auditLog);
        $this->getEntityManager()->flush();
    }
}
