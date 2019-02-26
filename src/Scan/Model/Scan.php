<?php

declare(strict_types=1);

namespace GC\Scan\Model;

use DateTime;
use GC\Alliance\Model\Alliance;
use GC\Player\Model\Player;

/**
 * @Table(name="scan", indexes={@Index(name="fk-scan-alliance_id", columns={"alliance_id"}), @Index(name="fk-scan-player_id", columns={"player_id"})})
 * @Entity
 */
final class Scan
{
    /**
     * @var int
     *
     * @Column(name="scan_id", type="bigint", nullable=false)
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
     * @var \GC\Alliance\Model\Alliance|null
     *
     * @ManyToOne(targetEntity="\GC\Alliance\Model\Alliance")
     * @JoinColumns({
     *   @JoinColumn(name="alliance_id", referencedColumnName="alliance_id")
     * })
     */
    private $alliance;

    /**
     * @var \GC\Player\Model\Player|null
     *
     * @ManyToOne(targetEntity="\GC\Player\Model\Player")
     * @JoinColumns({
     *   @JoinColumn(name="player_id", referencedColumnName="player_id")
     * })
     */
    private $player;

    /**
     * @param string $dataJson
     * @param \GC\Player\Model\Player $player
     * @param \GC\Alliance\Model\Alliance|null $alliance
     *
     * @throws \Exception
     */
    public function __construct(string $dataJson, Player $player, ?Alliance $alliance = null)
    {
        $this->dataJson = $dataJson;
        $this->player = $player;
        $this->alliance = $alliance;
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
     * @return \GC\Alliance\Model\Alliance|null
     */
    public function getAlliance(): ?Alliance
    {
        return $this->alliance;
    }

    /**
     * @param \GC\Alliance\Model\Alliance|null $alliance
     *
     * @return void
     */
    public function setAlliance(?Alliance $alliance): void
    {
        $this->alliance = $alliance;
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