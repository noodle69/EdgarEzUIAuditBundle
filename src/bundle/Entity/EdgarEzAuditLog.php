<?php

namespace Edgar\EzUIAuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzAuditLog
 *
 * @ORM\Entity(repositoryClass="Edgar\EzUIAudit\Repository\EdgarEzAuditLogRepository")
 * @ORM\Table(name="edgar_ez_audit_log")
 */
class EdgarEzAuditLog
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
