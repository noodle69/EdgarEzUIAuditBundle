<?php

namespace Edgar\EzUIAuditBundle\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use JMS\TranslationBundle\Model\Message;
use Knp\Menu\ItemInterface;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM_AUDIT = 'main__audit';
    const ITEM_AUDIT_DASHBOARD = 'main__audit__dashboard';
    const ITEM_AUDIT_CONFIGURE = 'main__audit__configure';
    const ITEM_AUDIT_EXPORT = 'main__audit__export';

    /** @var PermissionResolver */
    private $permissionResolver;

    /**
     * ConfigureMenuListener constructor.
     *
     * @param PermissionResolver $permissionResolver
     */
    public function __construct(
        PermissionResolver $permissionResolver
    ) {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Add Audit menu.
     *
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        if ($this->permissionResolver->hasAccess('uiaudit', 'audit')) {
            $menu = $event->getMenu();

            $auditMenu = $menu->addChild(self::ITEM_AUDIT, []);

            $this->addAuditMenuItems($auditMenu);
        }
    }

    /**
     * Add audit menu items.
     *
     * @param ItemInterface $auditMenu
     */
    private function addAuditMenuItems(ItemInterface $auditMenu)
    {
        $menuItems = [];

        $menuItems[self::ITEM_AUDIT_DASHBOARD] = $auditMenu->addChild(
            self::ITEM_AUDIT_DASHBOARD,
            ['route' => 'edgar.audit.dashboard']
        );

        $menuItems[self::ITEM_AUDIT_CONFIGURE] = $auditMenu->addChild(
            self::ITEM_AUDIT_CONFIGURE,
            ['route' => 'edgar.audit.configure']
        );

        $menuItems[self::ITEM_AUDIT_EXPORT] = $auditMenu->addChild(
            self::ITEM_AUDIT_EXPORT,
            ['route' => 'edgar.audit.export']
        );
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_AUDIT, 'messages'))->setDesc('Audit'),
            (new Message(self::ITEM_AUDIT_DASHBOARD, 'messages'))->setDesc('Dashboard'),
            (new Message(self::ITEM_AUDIT_CONFIGURE, 'messages'))->setDesc('Configure'),
            (new Message(self::ITEM_AUDIT_EXPORT, 'messages'))->setDesc('Export'),
        ];
    }
}
