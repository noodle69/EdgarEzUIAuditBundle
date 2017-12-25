<?php

namespace Edgar\EzAuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzAuditConfiguration
 *
 * @ORM\Entity(repositoryClass="Edgar\EzAudit\Repository\EdgarEzAuditConfigurationRepository")
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

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
