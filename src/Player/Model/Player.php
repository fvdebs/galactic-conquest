<?php

declare(strict_types=1);

namespace GC\Player\Model;

/**
 * @Table(name="player", indexes={@Index(name="fk-player-universe_id", columns={"universe_id"}), @Index(name="fk-player-faction_id", columns={"faction_id"}), @Index(name="fk-player-galaxy_id", columns={"galaxy_id"}), @Index(name="fk-player-user_id", columns={"user_id"})})
 * @Entity
 */
class Player
{
    /**
     * @var int
     *
     * @Column(name="player_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerId;

    /**
     * @var int
     *
     * @Column(name="galaxy_position", type="integer", nullable=false)
     */
    private $galaxyPosition;

    /**
     * @var int|null
     *
     * @Column(name="metal", type="bigint", nullable=true)
     */
    private $metal;

    /**
     * @var int|null
     *
     * @Column(name="crystal", type="bigint", nullable=true)
     */
    private $crystal;

    /**
     * @var int|null
     *
     * @Column(name="extractor_metal", type="integer", nullable=true)
     */
    private $extractorMetal;

    /**
     * @var int|null
     *
     * @Column(name="extractor_crystal", type="integer", nullable=true)
     */
    private $extractorCrystal;

    /**
     * @var int|null
     *
     * @Column(name="scan_relays", type="integer", nullable=true)
     */
    private $scanRelays;

    /**
     * @var int|null
     *
     * @Column(name="scan_blocker", type="integer", nullable=true)
     */
    private $scanBlocker;

    /**
     * @var bool|null
     *
     * @Column(name="is_alliance_scanner", type="boolean", nullable=true)
     */
    private $isAllianceScanner;

    /**
     * @var int|null
     *
     * @Column(name="alliance_scan_relays", type="integer", nullable=true)
     */
    private $allianceScanRelays;

    /**
     * @var int|null
     *
     * @Column(name="points", type="bigint", nullable=true)
     */
    private $points;

    /**
     * @var int|null
     *
     * @Column(name="ranking_position", type="integer", nullable=true)
     */
    private $rankingPosition;

    /**
     * @var \Faction
     *
     * @ManyToOne(targetEntity="Faction")
     * @JoinColumns({
     *   @JoinColumn(name="faction_id", referencedColumnName="faction_id")
     * })
     */
    private $faction;

    /**
     * @var \Galaxy
     *
     * @ManyToOne(targetEntity="Galaxy")
     * @JoinColumns({
     *   @JoinColumn(name="galaxy_id", referencedColumnName="galaxy_id")
     * })
     */
    private $galaxy;

    /**
     * @var \Universe
     *
     * @ManyToOne(targetEntity="Universe")
     * @JoinColumns({
     *   @JoinColumn(name="universe_id", referencedColumnName="universe_id")
     * })
     */
    private $universe;

    /**
     * @var \User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;


}
