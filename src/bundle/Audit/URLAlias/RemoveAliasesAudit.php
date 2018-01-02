<?php

namespace Edgar\EzUIAuditBundle\Audit\URLAlias;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class RemoveAliasesAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\URLAliasService\RemoveAliasesSignal) {
            return;
        }
    }
}
