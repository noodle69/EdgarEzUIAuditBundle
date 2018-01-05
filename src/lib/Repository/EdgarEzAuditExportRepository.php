<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;

class EdgarEzAuditExportRepository extends EntityRepository
{
    public function buildQuery(): QueryBuilder
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('e')
            ->from(EdgarEzAuditExport::class, 'e')
            ->orderBy('e.date', 'DESC');

        return $queryBuilder;
    }

}
