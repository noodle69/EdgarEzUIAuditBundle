<?php

namespace Edgar\EzUIAuditBundle\Audit\URL;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class UpdateUrlAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\URLService\UpdateUrlSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'urlId' => $signal->urlId,
        ];

        $this->auditService->log($this);
    }
}
