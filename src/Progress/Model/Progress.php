<?php

declare(strict_types=1);

namespace GC\Progress\Model;

/**
 * @Table(name="progress")
 * @Entity
 */
class Progress
{
    public const SOURCE_TYPE_PLAYER = 'player';
    public const SOURCE_TYPE_GALAXY = 'galaxy';
    public const SOURCE_TYPE_ALLIANCE = 'alliance';

    public const TARGET_TYPE_TECHNOLOGY = 'technology';
    public const TARGET_TYPE_UNIT = 'unit';
    public const TARGET_TYPE_SCAN_RELAY = 'scan_relay';
    public const TARGET_TYPE_SCAN_BLOCKER = 'scan_blocker';

    /**
     * @var int
     *
     * @Column(name="progress_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $progressId;

    /**
     * @var string
     *
     * @Column(name="source_type", type="string", length=150, nullable=false)
     */
    private $sourceType;

    /**
     * @var int
     *
     * @Column(name="source_reference_id", type="bigint", nullable=false)
     */
    private $sourceReferenceId;

    /**
     * @var string
     *
     * @Column(name="target_type", type="string", length=150, nullable=false)
     */
    private $targetType;

    /**
     * @var int
     *
     * @Column(name="target_reference_id", type="bigint", nullable=false)
     */
    private $targetReferenceId;

    /**
     * @var int
     *
     * @Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var int
     *
     * @Column(name="ticks_left", type="integer", nullable=false)
     */
    private $ticksLeft;

    /**
     * @param string $sourceType
     * @param int $sourceReferenceId
     * @param string $targetType
     * @param int $targetReferenceId
     * @param int $ticksLeft
     * @param int $quantity
     *
     */
    public function __construct(string $sourceType, int $sourceReferenceId, string $targetType, int $targetReferenceId, int $ticksLeft, int $quantity = 1)
    {
        $this->sourceType = $sourceType;
        $this->sourceReferenceId = $sourceReferenceId;
        $this->targetType = $targetType;
        $this->targetReferenceId = $targetReferenceId;
        $this->ticksLeft = $ticksLeft;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getProgressId(): int
    {
        return $this->progressId;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * @return int
     */
    public function getSourceReferenceId(): int
    {
        return $this->sourceReferenceId;
    }

    /**
     * @return string
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * @return int
     */
    public function getTargetReferenceId(): int
    {
        return $this->targetReferenceId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getTicksLeft(): int
    {
        return $this->ticksLeft;
    }
}