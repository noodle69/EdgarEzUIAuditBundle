<?php

namespace Edgar\EzUIAuditBundle\Audit\URLAlias;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class CreateGlobalUrlAliasAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\URLAliasService\CreateGlobalUrlAliasSignal) {
            return;
        }
    }
}
