<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class AddRelationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\AddRelationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'dstContentId' => $signal->dstContentId,
            'srcContentId' => $signal->srcContentId,
            'srcVersionNo' => $signal->srcVersionNo,
        ];

        $this->auditService->log($this);
    }
}
