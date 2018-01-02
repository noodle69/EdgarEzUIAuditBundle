<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class RemovePolicyByRoleDraftAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\RoleService\RemovePolicyByRoleDraftSignal) {
            return;
        }
    }
}