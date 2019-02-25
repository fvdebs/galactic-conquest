<?php

declare(strict_types=1);

namespace GC\Scan\Model;

/**
 * @Table(name="scan", indexes={@Index(name="fk-scan-alliance_id", columns={"alliance_id"}), @Index(name="fk-scan-player_id", columns={"player_id"})})
 * @Entity
 */
class Scan
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
     * @var \DateTime
     *
     * @Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Alliance
     *
     * @ManyToOne(targetEntity="Alliance")
     * @JoinColumns({
     *   @JoinColumn(name="alliance_id", referencedColumnName="alliance_id")
     * })
     */
    private $alliance;

    /**
     * @var \Player
     *
     * @ManyToOne(targetEntity="Player")
     * @JoinColumns({
     *   @JoinColumn(name="player_id", referencedColumnName="player_id")
     * })
     */
    private $player;


}
