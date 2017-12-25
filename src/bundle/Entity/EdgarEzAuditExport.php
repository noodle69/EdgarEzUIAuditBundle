<?php

namespace Edgar\EzAuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzAuditExport
 *
 * @ORM\Entity(repositoryClass="Edgar\EzAudit\Repository\EdgarEzAuditExportRepository")
 * @ORM\Table(name="edgar_ez_audit_export")
 */

class EdgarEzAuditExport
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
