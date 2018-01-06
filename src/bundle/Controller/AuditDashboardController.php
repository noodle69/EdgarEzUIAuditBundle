<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\ExportAuditData;
use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAudit\Form\Factory\ExportFormFactory;
use Edgar\EzUIAudit\Form\Factory\FormFactory;
use Edgar\EzUIAudit\Form\Mapper\PagerContentToLogMapper;
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

class AuditDashboardController extends BaseController
{
    /** @var FormFactory  */
    protected $formFactory;

    /** @var ExportFormFactory  */
    protected $exportFormFactory;

    /** @var SubmitHandler  */
    protected $submitHandler;

    /** @var AuditService  */
    protected $auditService;

    /** @var PermissionResolver  */
    protected $permissionResolver;

    /** @var NotificationHandlerInterface  */
    protected $notificationHandler;

    /** @var TranslatorInterface  */
    protected $translator;

    /** @var PagerContentToLogMapper  */
    protected $pagerContentToLogMapper;

    public function __construct(
        FormFactory $formFactory,
        ExportFormFactory $exportFormFactory,
        SubmitHandler $submitHandler,
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        PagerContentToLogMapper $pagerContentToLogMapper
    ) {
        parent::__construct($auditService, $permissionResolver, $notificationHandler, $translator);
        $this->formFactory = $formFactory;
        $this->exportFormFactory = $exportFormFactory;
        $this->submitHandler = $submitHandler;
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->pagerContentToLogMapper = $pagerContentToLogMapper;
    }

    public function dashboardAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'bookmark');

        $filterAuditType = $this->formFactory->filterAudit(
            new FilterAuditData()
        );
        $filterAuditType->handleRequest($request);
        $filterAuditType->getData()->setPage($request->get('page', 1));

        if ($filterAuditType->isSubmitted()) {
            $result = $this->submitHandler->handle($filterAuditType, function (FilterAuditData $data) use ($filterAuditType) {
                $limit = $data->getLimit();
                $page = $data->getPage();

                $query = $this->auditService->buildLogQuery($data);
                $pagerfanta = new Pagerfanta(
                    new DoctrineORMAdapter($query)
                );

                $pagerfanta->setMaxPerPage($limit);
                $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

                $exportAuditType = $this->exportFormFactory->exportAudit(
                    new ExportAuditData(
                        $data->getAuditTypes(),
                        $data->getDateStart(),
                        $data->getDateEnd()
                    )
                );

                return $this->render('@EdgarEzUIAudit/audit/dashboard.html.twig', [
                    'form_filter_audit' => $filterAuditType->createView(),
                    'form_export_audit' => $exportAuditType->createView(),
                    'results' => $this->pagerContentToLogMapper->map($pagerfanta),
                    'pager' => $pagerfanta,
                ]);
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzUIAudit/audit/dashboard.html.twig', [
            'form_filter_audit' => $filterAuditType->createView(),
        ]);
    }


}
