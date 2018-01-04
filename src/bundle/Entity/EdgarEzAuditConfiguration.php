<?php

namespace Edgar\EzUIAuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzAuditConfiguration
 *
 * @ORM\Entity(repositoryClass="Edgar\EzUIAudit\Repository\EdgarEzAuditConfigurationRepository")
 * @ORM\Table(name="edgar_ez_audit_configuration")
 */
class EdgarEzAuditConfiguration
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="audits", type="array", nullable=false)
     */
    private $audits;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setAudits(array $audits): self
    {
        $this->audits = $audits;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAudits(): array
    {
        return $this->audits;
    }
}
