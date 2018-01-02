<?php

namespace Edgar\EzUIAuditBundle\Cron;

use Doctrine\ORM\EntityManager;
use Edgar\Cron\Cron\AbstractCron;
use Edgar\EzUIAuditBundle\Entity\EdgarEzAuditExport;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuditExportCron extends AbstractCron
{
    private $exportRepository;

    public function __construct(?string $name = null, EntityManager $entityManager)
    {
        parent::__construct($name);
        $this->exportRepository = $entityManager->getRepository(EdgarEzAuditExport::class);
    }

    protected function configure()
    {
        $this
            ->setName('edgarez:audit:export')
            ->setDescription('Export audit informations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
