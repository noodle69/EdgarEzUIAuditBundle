<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class SwapLocationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\SwapLocationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'content1Id' => $signal->content1Id,
            'content2Id' => $signal->content2Id,
            'location1Id' => $signal->location1Id,
            'location2Id' => $signal->location2Id,
            'parentLocation1Id' => $signal->parentLocation1Id,
            'parentLocation2Id' => $signal->parentLocation2Id,
        ];

        $this->auditService->log($this);
    }
}
