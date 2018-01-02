<?php

namespace Edgar\EzUIAudit\Audit;

use eZ\Publish\Core\SignalSlot\Signal;

interface AuditInterface
{
    public function receive(Signal $signal);
}
