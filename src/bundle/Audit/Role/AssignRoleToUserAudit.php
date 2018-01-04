<?php

namespace Edgar\EzUIAuditBundle\Audit\Role;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class AssignRoleToUserAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\RoleService\AssignRoleToUserSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'roleId' => $signal->roleId,
            'userId' => $signal->userId,
            'roleLimitation identifier' => $signal->roleLimitation->getIdentifier(),
        ];

        $this->auditService->log($this);
    }
}
