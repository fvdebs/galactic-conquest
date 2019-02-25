<?php

declare(strict_types=1);

namespace GC\Player\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="player_technology", indexes={@Index(name="fk-player_technology-player_id", columns={"player_id"}), @Index(name="fk-player_technology-technology_id", columns={"technology_id"})})
 * @Entity
 */
final class PlayerTechnology
{
    /**
     * @var int
     *
     * @Column(name="player_technology_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerTechnologyId;

    /**
     * @var \GC\Player\Model\Player
     *
     * @ManyToOne(targetEntity="\GC\Player\Model\Player")
     * @JoinColumns({
     *   @JoinColumn(name="player_id", referencedColumnName="player_id")
     * })
     */
    private $player;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumns({
     *   @JoinColumn(name="technology_id", referencedColumnName="technology_id")
     * })
     */
    private $technology;

    /**
     * @param \GC\Player\Model\Player $player
     * @param \GC\Technology\Model\Technology $technology
     */
    public function __construct(Player $player, Technology $technology)
    {
        $this->player = $player;
        $this->technology = $technology;
    }

    /**
     * @return int
     */
    public function getPlayerTechnologyId(): int
    {
        return $this->playerTechnologyId;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getTechnology():Technology
    {
        return $this->technology;
    }
}