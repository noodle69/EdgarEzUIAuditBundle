<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAudit\Form\Factory\ExportFormFactory;
use Edgar\EzUIAudit\Form\Mapper\PagerContentToExportMapper;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AuditExportController extends BaseController
{
    /** @var PagerContentToExportMapper */
    protected $pagerContentToExportMapper;

    /** @var ExportFormFactory */
    protected $exportFormFactory;

    /** @var SubmitHandler */
    protected $submitHandler;

    /**
     * AuditExportController constructor.
     *
     * @param AuditService $auditService
     * @param PermissionResolver $permissionResolver
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param PagerContentToExportMapper $pagerContentToExportMapper
     * @param ExportFormFactory $exportFormFactory
     * @param SubmitHandler $submitHandler
     */
    public function __construct(
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        PagerContentToExportMapper $pagerContentToExportMapper,
        ExportFormFactory $exportFormFactory,
        SubmitHandler $submitHandler
    ) {
        parent::__construct($auditService, $permissionResolver, $notificationHandler, $translator);
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->pagerContentToExportMapper = $pagerContentToExportMapper;
        $this->exportFormFactory = $exportFormFactory;
        $this->submitHandler = $submitHandler;
    }

    /**
     * Export audit informations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'export');

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $query = $this->auditService->buildExportQuery();
        $pagerfanta = new Pagerfanta(
            new DoctrineORMAdapter($query)
        );

        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        return $this->render('@EdgarEzUIAudit/audit/export.html.twig', [
            'exports' => $this->pagerContentToExportMapper->map($pagerfanta),
            'pager' => $pagerfanta,
        ]);
    }

    /**
     * Register audit export transaction.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function askExportAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'export');

        $exportAuditType = $this->exportFormFactory->exportAudit(
            new ExportAuditData()
        );
        $exportAuditType->handleRequest($request);

        if ($exportAuditType->isSubmitted()) {
            $result = $this->submitHandler->handle($exportAuditType, function (ExportAuditData $data) use ($exportAuditType) {
                $this->auditService->saveExport($data);

                return new RedirectResponse($this->generateUrl('edgar.audit.export', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.audit.export', []));
    }
}
