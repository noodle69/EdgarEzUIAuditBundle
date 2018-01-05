<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Form\Factory\ConfigureFormFactory;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AuditConfigureController extends Controller
{
    /** @var ConfigureFormFactory  */
    protected $configureFormFactory;

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

    public function __construct(
        ConfigureFormFactory $configureFormFactory,
        SubmitHandler $submitHandler,
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator
    ) {
        $this->configureFormFactory = $configureFormFactory;
        $this->submitHandler = $submitHandler;
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
    }

    public function configureAction(Request $request): Response
    {
        if (!$this->permissionResolver->hasAccess('uiaudit', 'configure')) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuiaudit.permission.failed',
                    [],
                    'edgarezuiaudit'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        $configureAuditType = $this->configureFormFactory->configureAudit(
            $this->auditService->getAuditConfiguration()
        );
        $configureAuditType->handleRequest($request);

        if ($configureAuditType->isSubmitted() && $configureAuditType->isValid()) {
            $result = $this->submitHandler->handle($configureAuditType, function (ConfigureAuditData $data) use ($configureAuditType) {
                $this->auditService->saveAuditConfiguration($data->getAuditTypes());

                $this->notificationHandler->success(
                    $this->translator->trans(
                        'edgar.ezuiaudit.configure.updated',
                        [],
                        'edgarezuiaudit'
                    )
                );

                return $this->render('@EdgarEzUIAudit/audit/configure.html.twig', [
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
}
