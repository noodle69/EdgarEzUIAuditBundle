<?php

namespace Edgar\EzUIAudit\Form\Data;

use Edgar\EzUIAudit\Audit\AuditInterface;
use Symfony\Component\Validator\Constraints as Assert;

class FilterAuditData
{
    /**
     * @var int
     *
     * @Assert\Range(
     *     max = 1000
     * )
     */
    private $limit;

    /** @var int */
    private $page;

    /**
     * @var AuditInterface[]
     *
     * @Assert\NotBlank()
     */
    private $audit_types;

    public function __construct(
        int $limit = 10,
        int $page = 1,
        ?array $audit_types = []
    ) {
        $this->limit = $limit;
        $this->page = $page;
        $this->audit_types = $audit_types;
    }

    /**
     * @param int $limit
     *
     * @return FilterAuditData
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return FilterAuditData
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setAuditTypes(?array $audity_types): self
    {
        $this->audit_types = $audity_types;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    public function getAuditTypes(): ?array
    {
        return $this->audit_types;
    }
}
