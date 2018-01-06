<?php

namespace Edgar\EzUIAuditBundle\Controller;

use Edgar\EzUIAuditBundle\Service\AuditService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;

abstract class BaseController extends Controller
{
    /** @var AuditService  */
    protected $auditService;

    /** @var PermissionResolver  */
    protected $permissionResolver;

    /** @var NotificationHandlerInterface  */
    protected $notificationHandler;

    /** @var TranslatorInterface  */
    protected $translator;

    public function __construct(
        AuditService $auditService,
        PermissionResolver $permissionResolver,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator
    ) {
        $this->auditService = $auditService;
        $this->permissionResolver = $permissionResolver;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
    }

    protected function permissionAccess(string $module, string $function): ?RedirectResponse
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuiaudit.permission.failed',
                    [],
                    'edgarezuiaudit'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        return null;
    }
}
