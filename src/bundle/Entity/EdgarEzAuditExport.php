<?php

namespace Edgar\EzUIAuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzAuditExport
 *
 * @ORM\Entity(repositoryClass="Edgar\EzUIAudit\Repository\EdgarEzAuditExportRepository")
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

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var array
     *
     * @ORM\Column(name="audits", type="array", nullable=false)
     */
    private $audits;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=false)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=false)
     */
    private $dateEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", nullable=true)
     */
    private $file;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function setAudits(array $audits): self
    {
        $this->audits = $audits;
        return $this;
    }

    public function setDateStart(\DateTime $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function setDateEnd(\DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getAudits(): array
    {
        return $this->audits;
    }

    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }
}
