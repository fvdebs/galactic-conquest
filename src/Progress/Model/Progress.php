<?php

declare(strict_types=1);

namespace GC\Progress\Model;

/**
 * @Table(name="progress")
 * @Entity
 */
class Progress
{
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


}
