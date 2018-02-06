<?php

namespace Edgar\EzUIAudit\Form\Data;

class AuditData
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $name;

    /**
     * @param string $identifier
     *
     * @return AuditData
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getGroupName(): string
    {
        $identifier = explode('/', $this->identifier);

        return $identifier[0];
    }

    /**
     * @param string $name
     *
     * @return AuditData
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
