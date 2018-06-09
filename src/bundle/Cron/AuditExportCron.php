<?php

namespace Edgar\EzUIAuditBundle\Cron;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Edgar\Cron\Cron\AbstractCron;
use Edgar\Cron\Repository\EdgarCronRepository;
use Edgar\EzUIAudit\Repository\EdgarEzAuditExportRepository;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Edgar\EzUIAuditBundle\Service\AuditService;
use Exporter\Handler;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use Exporter\Writer\CsvWriter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuditExportCron extends AbstractCron
{
    const EXPORT_DIR = '_export';

    /** @var EdgarEzAuditExportRepository */
    private $exportRepository;

    /** @var AuditService */
    private $auditService;

    /** @var string  */
    private $kernelRootDir;

    /** @var string  */
    private $varDir;

    /** @var string */
    private $storageDir;

    /**
     * AuditExportCron constructor.
     *
     * @param null|string $name
     * @param EntityManager $entityManager
     * @param AuditService $auditService
     * @param string $kernelRootDir
     * @param string $varDir
     * @param string $storageDir
     */
    public function __construct(
        ?string $name = null,
        EntityManager $entityManager,
        AuditService $auditService,
        string $kernelRootDir,
        string $varDir,
        string $storageDir
    ) {
        parent::__construct($name);
        $this->exportRepository = $entityManager->getRepository(EdgarEzAuditExport::class);
        $this->auditService = $auditService;
        $this->kernelRootDir = $kernelRootDir;
        $this->varDir = $varDir;
        $this->storageDir = $storageDir;
    }

    /**
     * Configure cron.
     */
    protected function configure()
    {
        $this
            ->setName('edgarez:audit:export')
            ->setDescription('Export audit informations');
    }

    /**
     * Execute cron export.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $export = $this->exportRepository->startExport();

        if ($export) {
            /** @var Query $query */
            $query = $this->exportRepository->processExport($export);

            $source = new DoctrineORMQuerySourceIterator($query, [
                'user_id', 'infos', 'date', 'group_name', 'audit_name',
            ]);
            $now = new \DateTime();

            $exportDir = $this->kernelRootDir . '/../web/' . $this->varDir . '/' . $this->storageDir. '/' . self::EXPORT_DIR;
            if (!is_dir($exportDir)) {
                if (!@mkdir($exportDir, 0777, true)) {
                    $output->writeln('Fial to create export directory');
                    try {
                        $this->exportRepository->setStatus($export, EdgarEzAuditExportRepository::STATUS_KO);
                    } catch (ORMException $e) {
                        $output->writeln('Fail to create export directory');
                    }
                    return EdgarCronRepository::STATUS_ERROR;
                }
            }

            $csvFile = $exportDir . '/audit_export_' . $now->getTimestamp() . '.csv';
            $writer = new CsvWriter($csvFile);
            Handler::create($source, $writer)->export();

            try {
                $this->exportRepository->endExport($export, $csvFile);
            } catch (ORMException $e) {
                $output->writeln('Fail to export audit: ' . $e->getMessage());
                try {
                    $this->exportRepository->setStatus($export, EdgarEzAuditExportRepository::STATUS_KO);
                } catch (ORMException $e) {
                    $output->writeln('Fail to export audit: ' . $e->getMessage());
                }
                return EdgarCronRepository::STATUS_ERROR;
            }
        }
    }
}
