<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class CopyContentAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\CopyContentSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'dstContentId' => $signal->dstContentId,
            'srcContentId' => $signal->srcContentId,
            'srcVersionNo' => $signal->srcVersionNo,
            'dstParentLocationId' => $signal->dstParentLocationId,
            'dstVersionNo' => $signal->dstVersionNo,
        ];

        $this->auditService->log($this);
    }
}
