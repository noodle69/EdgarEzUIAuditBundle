<?php

namespace Edgar\EzUIAudit\Form\Data;

use Edgar\EzUIAudit\Audit\AuditInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigureAuditData
{
    /**
     * @var AuditInterface[]
     *
     * @Assert\NotBlank()
     */
    private $audit_types;

    public function __construct(
        ?array $audit_types = []
    ) {
        $this->audit_types = $audit_types;
    }

    public function setAuditTypes(?array $audity_types): self
    {
        $this->audit_types = $audity_types;

        return $this;
    }

    public function getAuditTypes(): ?array
    {
        return $this->audit_types;
    }
}
