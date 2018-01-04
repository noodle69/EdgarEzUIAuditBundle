<?php

namespace Edgar\EzUIAudit\Audit;

use eZ\Publish\Core\SignalSlot\Signal;

interface AuditInterface
{
    public function receive(Signal $signal);

    public function getInfos(): array;

    public function getName(): string;

    public function getGroup(): string;
}
