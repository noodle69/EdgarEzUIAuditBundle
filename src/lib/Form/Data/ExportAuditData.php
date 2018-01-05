<?php

namespace Edgar\EzUIAudit\Form\Data;

class ExportAuditData
{
    /** @var string|null  */
    private $audit_types = [];

    /** @var int|null  */
    private $date_start;

    /** @var int|null  */
    private $date_end;

    public function __construct(
        ?array $audit_types,
        ?\DateTime $date_start,
        ?\DateTime $date_end
    ) {
        $audits = [];
        if ($audit_types) {
            foreach ($audit_types as $audit_type) {
                $audits[] = $audit_type->getIdentifier();
            }
        }
        $this->audit_types = implode(',', $audits);
        $this->date_start = $date_start->getTimestamp();
        $this->date_end = $date_end->getTimestamp();
    }

    public function setAuditTypes(?string $audit_types): self
    {
        $this->audit_types = $audit_types;
        return $this;
    }

    public function setDateStart(?int $date_start): self
    {
        $this->date_start = $date_start;
        return $this;
    }

    public function setDateEnd(?int $date_end): self
    {
        $this->date_end = $date_end;
        return $this;
    }

    public function getAuditTypes(): ?string
    {
        return $this->audit_types;
    }

    public function getDateStart(): ?int
    {
        return $this->date_start;
    }

    public function getDateEnd(): ?int
    {
        return $this->date_end;
    }
}
