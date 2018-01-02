<?php

namespace Edgar\EzUIAuditBundle\Audit\URLWildcard;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class RemoveAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\URLWildcardService\RemoveSignal) {
            return;
        }
    }
}
