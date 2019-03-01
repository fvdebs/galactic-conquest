<?php

declare(strict_types=1);

namespace GC\Scan\Model;

use DateTime;
use GC\Player\Model\Player;

/**
 * @Table(name="scan")
 * @Entity(repositoryClass="GC\Scan\Model\ScanRepository")
 */
final class Scan
{
    /**
     * @var int
     *
     * @Column(name="scan_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $scanId;

    /**
     * @var string
     *
     * @Column(name="data_json", type="text", length=65535, nullable=false)
     */
    private $dataJson;

    /**
     * @var DateTime
     *
     * @Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \GC\Player\Model\Player|null
     *
     * @ManyToOne(targetEntity="\GC\Player\Model\Player")
     * @JoinColumn(name="player_id", referencedColumnName="player_id", nullable=true)
     */
    private $player;

    /**
     * @param string $dataJson
     * @param \GC\Player\Model\Player $player
     *
     * @throws \Exception
     */
    public function __construct(string $dataJson, Player $player)
    {
        $this->dataJson = $dataJson;
        $this->player = $player;
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getScanId(): int
    {
        return $this->scanId;
    }

    /**
     * @return string
     */
    public function getDataJson(): string
    {
        return $this->dataJson;
    }

    /**
     * @param string $dataJson
     *
     * @return void
     */
    public function setDataJson(string $dataJson): void
    {
        $this->dataJson = $dataJson;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @param \GC\Player\Model\Player|null $player
     *
     * @return void
     */
    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }
}