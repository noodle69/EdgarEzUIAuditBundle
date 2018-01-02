<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class PublishVersionAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\PublishVersionSignal) {
            return;
        }
    }
}