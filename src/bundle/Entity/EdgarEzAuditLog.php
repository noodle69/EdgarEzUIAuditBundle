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

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string", nullable=false)
     */
    private $groupName;

    /**
     * @var string
     *
     * @ORM\Column(name="audit_name", type="string", nullable=false)
     */
    private $auditName;

    /**
     * @var array
     *
     * @ORM\Column(name="infos", type="array", nullable=false)
     */
    private $infos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

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

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    public function setAuditName(string $auditName): self
    {
        $this->auditName = $auditName;
        return $this;
    }

    public function setInfos(array $infos): self
    {
        $this->infos = $infos;
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

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getAuditName(): string
    {
        return $this->auditName;
    }

    public function getInfos(): array
    {
        return $this->infos;
    }
}
