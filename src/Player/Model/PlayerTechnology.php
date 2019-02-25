<?php

declare(strict_types=1);

namespace GC\Player\Model;

/**
 * @Table(name="player_technology", indexes={@Index(name="fk-player_technology-player_id", columns={"player_id"}), @Index(name="fk-player_technology-technology_id", columns={"technology_id"})})
 * @Entity
 */
class PlayerTechnology
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
     * @var \Player
     *
     * @ManyToOne(targetEntity="Player")
     * @JoinColumns({
     *   @JoinColumn(name="player_id", referencedColumnName="player_id")
     * })
     */
    private $player;

    /**
     * @var \Technology
     *
     * @ManyToOne(targetEntity="Technology")
     * @JoinColumns({
     *   @JoinColumn(name="technology_id", referencedColumnName="technology_id")
     * })
     */
    private $technology;


}
