<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class UnhideLocationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\UnhideLocationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'locationId' => $signal->locationId,
            'currentVersionNo' => $signal->currentVersionNo,
            'parentLocationId' => $signal->parentLocationId,
            'contentId' => $signal->contentId,
        ];

        $this->auditService->log($this);
    }
}
