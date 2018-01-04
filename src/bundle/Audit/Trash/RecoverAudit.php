<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class RecoverAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\TrashService\RecoverSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'trashItemId' => $signal->trashItemId,
            'contentId' => $signal->contentId,
            'newParentLocationId' => $signal->newParentLocationId,
            'newLocationId' => $signal->newLocationId,
        ];

        $this->auditService->log($this);
    }
}
