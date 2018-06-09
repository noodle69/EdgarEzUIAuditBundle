<?php

namespace Edgar\EzUIAudit\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditLog;

class EdgarEzAuditExportRepository extends EntityRepository
{
    public const STATUS_INIT = 0;
    public const STATUS_PROGRESS = 1;
    public const STATUS_OK = 2;
    public const STATUS_KO = -1;

    /**
     * @return QueryBuilder
     */
    public function buildQuery(): QueryBuilder
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('e')
            ->from(EdgarEzAuditExport::class, 'e')
            ->orderBy('e.date', 'DESC');

        return $queryBuilder;
    }

    /**
     * @param ExportAuditData $data
     * @param int $userId
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ExportAuditData $data, int $userId)
    {
        $auditData = new EdgarEzAuditExport();
        $auditData->setUserId($userId);
        $auditData->setDate(new \DateTime());

        $dateStart = new \DateTime();
        $dateStart->setTimestamp($data->getDateStart());
        $auditData->setDateStart($dateStart);

        $dateEnd = new \DateTime();
        $dateEnd->setTimestamp($data->getDateEnd());
        $auditData->setDateEnd($dateEnd);

        $auditData->setAudits(explode(',', $data->getAuditTypes()));
        $auditData->setStatus(self::STATUS_INIT);

        $this->getEntityManager()->persist($auditData);
        $this->getEntityManager()->flush();
    }

    /**
     * @return EdgarEzAuditExport|null
     */
    public function startExport(): ?EdgarEzAuditExport
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder->select('e')
            ->from(EdgarEzAuditExport::class, 'e')
            ->orderBy('e.date', 'DESC')
            ->where($queryBuilder->expr()->eq('e.status', ':status'))
            ->setMaxResults(1)
            ->setParameter('status', self::STATUS_INIT)
            ->getQuery();

        try {
            /** @var EdgarEzAuditExport $export */
            $export = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }

        if ($export) {
            try {
                $export->setStatus(self::STATUS_PROGRESS);
                $entityManager->persist($export);
                $entityManager->flush();
            } catch (ORMException $e) {
                return null;
            }

            return $export;
        }

        return null;
    }

    /**
     * @param EdgarEzAuditExport $export
     *
     * @return Query
     */
    public function processExport(EdgarEzAuditExport $export): IterableResult
    {
        $entityManager = $this->getEntityManager();

        $auditIdentifiers = [];
        if ($export->getAudits() && count($export->getAudits())) {
            $auditIdentifiers = $export->getAudits();
            $qbFilterAudit = $entityManager->createQueryBuilder();
            $qbFilterAudit->expr()->in('l.audit_identifier', ':audit_identifiers');
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
            ->setParameter('date_start', $export->getDateStart())
            ->setParameter('date_end', $export->getDateEnd());

        return $queryBuilder->getQuery()->iterate(null, Query::HYDRATE_ARRAY);
    }

    /**
     * @param EdgarEzAuditExport $export
     * @param string $file
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function endExport(EdgarEzAuditExport $export, string $file)
    {
        $export->setStatus(self::STATUS_OK);
        $export->setFile($file);
        $this->getEntityManager()->persist($export);
        $this->getEntityManager()->flush();
    }

    /**
     * @param EdgarEzAuditExport $export
     * @param int $status
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setStatus(EdgarEzAuditExport $export, int $status)
    {
        $export->setStatus($status);
        $this->getEntityManager()->persist($export);
        $this->getEntityManager()->flush();
    }
}
