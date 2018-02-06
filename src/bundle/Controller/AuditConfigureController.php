<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAudit\Form\Data\ConfigureAuditData;
use Edgar\EzUIAudit\Form\Factory\ConfigureFormFactory;
use Edgar\EzUIAudit\Form\SubmitHandler;
use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AuditConfigureController extends BaseController
{
    /** @var ConfigureFormFactory */
    protected $configureFormFactory;

    /** @var SubmitHandler */
    protected $submitHandler;

    /**
     * AuditConfigureController constructor.
     *
     * @param ConfigureFormFactory $configureFormFactory
     * @param SubmitHandler $submitHandler
     * @param AuditService $auditService
     * @param PermissionResolver $permissionResolver
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ConfigureFormFactory $configureFormFactory,
        SubmitHandler $submitHandler,
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator
    ) {
        parent::__construct($auditService, $permissionResolver, $notificationHandler, $translator);
        $this->configureFormFactory = $configureFormFactory;
        $this->submitHandler = $submitHandler;
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
    }

    /**
     * Configure which signals should be audited.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function configureAction(Request $request): Response
    {
        $this->permissionAccess('uiaudit', 'configure');

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
