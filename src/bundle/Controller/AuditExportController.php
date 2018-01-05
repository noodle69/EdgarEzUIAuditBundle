<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Mapper\PagerContentToExportMapper;
use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AuditExportController extends Controller
{
    /** @var AuditService  */
    protected $auditService;

    /** @var PermissionResolver  */
    protected $permissionResolver;

    /** @var NotificationHandlerInterface  */
    protected $notificationHandler;

    /** @var TranslatorInterface  */
    protected $translator;

    /** @var PagerContentToExportMapper  */
    protected $pagerContentToExportMapper;

    public function __construct(
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        PagerContentToExportMapper $pagerContentToExportMapper
    ) {
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->pagerContentToExportMapper = $pagerContentToExportMapper;
    }

    public function exportAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'export')) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuiaudit.permission.failed',
                    [],
                    'edgarezuiaudit'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

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

    public function askExport(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'export')) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuiaudit.permission.failed',
                    [],
                    'edgarezuiaudit'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        return new RedirectResponse($this->generateUrl('edgar.audit.export', []));
    }
}
