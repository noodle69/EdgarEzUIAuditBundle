<?php

namespace Edgar\EzUIAuditBundle\Audit\Location;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class HideLocationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\HideLocationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'contentId' => $signal->contentId,
            'locationId' => $signal->locationId,
            'parentLocationId' => $signal->parentLocationId,
            'currentVersionNo' => $signal->currentVersionNo,
        ];

        $this->auditService->log($this);
    }
}
