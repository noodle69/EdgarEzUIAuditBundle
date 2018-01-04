<?php

namespace Edgar\EzUIAuditBundle\Audit\ContentType;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class UnassignContentTypeGroupAudit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentTypeService\UnassignContentTypeGroupSignal
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            'contentTypeGroupId' => $signal->contentTypeGroupId,
            'contentTypeId' => $signal->contentTypeId,
        ];

        $this->auditService->log($this);
    }
}
