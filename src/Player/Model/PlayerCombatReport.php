<?php

declare(strict_types=1);

namespace GC\Player\Model;

use DateTime;

use function str_shuffle;
use function substr;

/**
 * @Table(name="player_combat_report")
 * @Entity(repositoryClass="GC\Player\Model\PlayerCombatReportRepository")
 */
class PlayerCombatReport
{
    /**
     * @var int
     *
     * @Column(name="combat_report_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $combatReportId;

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
     * @var string
     *
     * @Column(name="external_id", type="string", length=60, nullable=false)
     */
    private $externalId;

    /**
     * @var \GC\Player\Model\Player
     *
     * @ManyToOne(targetEntity="GC\Player\Model\Player", inversedBy="playerCombatReports")
     * @JoinColumn(name="player_id", referencedColumnName="player_id", nullable=false)
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

        $this->generateExternalId();
    }

    /**
     * @return int
     */
    public function getCombatReportId(): int
    {
        return $this->combatReportId;
    }

    /**
     * @return string
     */
    public function getDataJson(): string
    {
        return $this->dataJson;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function generateExternalId(): string
    {
        $this->externalId = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 15);

        return $this->externalId;
    }
}
