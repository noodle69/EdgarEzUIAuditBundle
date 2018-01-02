<?php

namespace Edgar\EzUIAuditBundle\Audit\Trash;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class DeleteTrashItemAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\TrashService\DeleteTrashItemSignal) {
            return;
        }
    }
}
