<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Form\Data\FilterAuditData;
use Edgar\EzUIAudit\Form\Factory\ConfigureFormFactory;
use Edgar\EzUIAudit\Form\Factory\FormFactory;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditController extends Controller
{
    /** @var FormFactory  */
    protected $formFactory;

    /** @var ConfigureFormFactory  */
    protected $configureFormFactory;

    /** @var SubmitHandler  */
    protected $submitHandler;

    /** @var AuditService  */
    protected $auditService;

    /** @var PermissionResolver  */
    protected $permissionResolver;

    public function __construct(
        FormFactory $formFactory,
        ConfigureFormFactory $configureFormFactory,
        SubmitHandler $submitHandler,
        AuditService $auditService,
        PermissionResolver $permissionResolver
    ) {
        $this->formFactory = $formFactory;
        $this->configureFormFactory = $configureFormFactory;
        $this->submitHandler = $submitHandler;
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
    }

    public function dashboardAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'dashboard')) {
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        $filterAuditType = $this->formFactory->filterAudit(
            new FilterAuditData()
        );
        $filterAuditType->handleRequest($request);

        if ($filterAuditType->isSubmitted() && $filterAuditType->isValid()) {
            $result = $this->submitHandler->handle($filterAuditType, function (FilterAuditData $data) use ($filterAuditType) {
                $limit = $data->getLimit();
                $page = $data->getPage();

                return $this->render('@EdgarEzUIContentsByType/content/list.html.twig', [
                    'form_filter_audit' => $filterAuditType->createView(),
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

    public function configureAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'configure')) {
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        $configureAuditType = $this->configureFormFactory->configureAudit(
            $this->auditService->getAuditConfiguration()
        );
        $configureAuditType->handleRequest($request);

        if ($configureAuditType->isSubmitted() && $configureAuditType->isValid()) {
            $result = $this->submitHandler->handle($configureAuditType, function (ConfigureAuditData $data) use ($configureAuditType) {
                $this->auditService->saveAuditConfiguration($data->getAuditTypes());
                return $this->render('@EdgarEzUIContentsByType/content/list.html.twig', [
                    'form_configure_audit' => $configureAuditType->createView(),
                ]);
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzUIAudit/audit/configure.html.twig', [
            'form_configure_audit' => $configureAuditType->createView(),
        ]);
    }

    public function exportAction(): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'export')) {
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        return $this->render('@EdgarEzUIAudit/audit/export.html.twig', [
        ]);
    }
}
