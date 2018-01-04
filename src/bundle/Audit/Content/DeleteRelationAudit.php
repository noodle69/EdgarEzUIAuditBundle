<?php

namespace Edgar\EzUIAuditBundle\Audit\Content;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class DeleteRelationAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\DeleteRelationSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'srcVersionNo' => $signal->srcVersionNo,
            'srcContentId' => $signal->srcContentId,
            'dstContentId' => $signal->dstContentId,
        ];

        $this->auditService->log($this);
    }
}
