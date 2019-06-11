<?php

declare(strict_types=1);

namespace GC\Event\Model;

use DateTime;
use GC\Event\Exception\UnknownTranslationException;
use GC\Player\Model\Player;

use function json_decode;
use function json_encode;

/**
 * @Table(name="event")
 * @Entity(repositoryClass="GC\Event\Model\EventRepository")
 */
class Event
{
    public const TYPE_PLAYER_TRADE = 1; // trade mit spieler
    public const TYPE_PLAYER_RELOCATE = 2; // Spielerumzug

    public const TYPE_ALLIANCE_TRADE = 3; // trade mit alliance
    public const TYPE_ALLIANCE_DONATION_RECEIVED = 4; // spende allianz

    public const TYPE_GALAXY_TRADE = 6; // trade mit galaxy
    public const TYPE_GALAXY_DONATION_RECEIVED = 7; // spende galaxy

    public const TYPE_PLAYER_FLEET_MOVE_ATTACK = 9;
    public const TYPE_PLAYER_FLEET_MOVE_DEFEND = 9;
    public const TYPE_PLAYER_FLEET_RECALL = 10;

    public const TYPE_PLAYER_COMBAT_REPORT_TICK_SOURCE = 11; // neuer angriffsbericht
    public const TYPE_PLAYER_COMBAT_REPORT_TICK_TARGET = 12; // neuer verteidigungsbericht
    public const TYPE_PLAYER_COMBAT_REPORT_SUMMARY_SOURCE = 13; // Zusammenfassender angriffsbericht
    public const TYPE_PLAYER_COMBAT_REPORT_SUMMARY_TARGET = 14; // Zusammenfassender verteidigungsbericht

    public const TYPE_PLAYER_SCAN_BLOCK_SOURCE = 15; // scan block verursacht
    public const TYPE_PLAYER_SCAN_BLOCK_TARGET = 16; // scan block erhalten
    public const TYPE_PLAYER_SCAN_SOURCE = 17; // hat einen spieler gescannt
    public const TYPE_PLAYER_SCAN_TARGET = 18; // wurde von spieler gescannt

    public const TYPE_PLAYER_BUILD_UNIT = 19;
    public const TYPE_PLAYER_BUILD_UNIT_CANCEL = 20;
    public const TYPE_PLAYER_BUILD_TECHNOLOGY = 21;
    public const TYPE_PLAYER_BUILD_TECHNOLOGY_CANCEL = 22;
    public const TYPE_PLAYER_BUILD_SCAN_RELAIS = 23;
    public const TYPE_PLAYER_BUILD_SCAN_RELAIS_CANCEL = 24;
    public const TYPE_PLAYER_BUILD_SCAN_BLOCKER = 25;
    public const TYPE_PLAYER_BUILD_SCAN_BLOCKER_CANCEL = 26;
    public const TYPE_PLAYER_BUILD_EXTRACTOR_METAL = 27;
    public const TYPE_PLAYER_BUILD_EXTRACTOR_CRYSTAL = 28;

    /**
     * @var int
     *
     * @Column(name="event_log_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $eventLogId;

    /**
     * @var string
     *
     * @Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var \GC\Player\Model\Player
     *
     * @ManyToOne(targetEntity="\GC\Player\Model\Player")
     * @JoinColumn(name="player_id", referencedColumnName="player_id", nullable=false)
     */
    private $player;

    /**
     * @var string
     *
     * @Column(name="text", type="text", length=65535, nullable=false)
     */
    private $data;

    /**
     * @var DateTime
     *
     * @Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @param \GC\Player\Model\Player $player
     * @param string $type
     * @param string[] $data
     */
    public function __construct(Player $player, string $type, array $data)
    {
        $this->player = $player;
        $this->type = $type;
        $this->data = json_encode($data);
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getEventLogId(): int
    {
        return $this->eventLogId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return json_decode($this->data);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string
    {
        switch($this->type) {
            default: throw new UnknownTranslationException($this);
        }
    }
}
