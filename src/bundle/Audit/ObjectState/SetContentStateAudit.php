<?php

namespace Edgar\EzUIAuditBundle\Audit\ObjectState;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class SetContentStateAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ObjectStateService\SetContentStateSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'objectStateGroupId' => $signal->objectStateGroupId,
            'objectStateId' => $signal->objectStateId,
            'contentId' => $signal->contentId,
        ];

        $this->auditService->log($this);
    }
}
