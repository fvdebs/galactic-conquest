<?php

declare(strict_types=1);

namespace GC\Combat\Report;

use DateTime;

final class CombatReportGeneratorResponse implements CombatReportGeneratorResponseInterface
{
    /**
     * @var string
     */
    private $renderedHtml;

    /**
     * @var bool
     */
    private $isPointOfViewReportTypeOffensive;

    /**
     * @var string
     */
    private $pointOfViewDisplayName;

    /**
     * @var string
     */
    private $targetName;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string[]
     */
    private $reportData;

    /**
     * @param string $renderedHtml
     * @param bool $isPointOfViewReportTypeOffensive
     * @param string $pointOfViewDisplayName
     * @param string $targetName
     * @param \DateTime $createdAt
     * @param string[] $reportData
     */
    public function __construct(
        string $renderedHtml,
        bool $isPointOfViewReportTypeOffensive,
        string $pointOfViewDisplayName,
        string $targetName,
        DateTime $createdAt,
        array $reportData
    ) {
        $this->renderedHtml = $renderedHtml;
        $this->isPointOfViewReportTypeOffensive = $isPointOfViewReportTypeOffensive;
        $this->pointOfViewDisplayName = $pointOfViewDisplayName;
        $this->targetName = $targetName;
        $this->createdAt = $createdAt;
        $this->reportData = $reportData;
    }

    /**
     * @return string
     */
    public function getRenderedHtml(): string
    {
        return $this->renderedHtml;
    }

    /**
     * @return bool
     */
    public function isPointOfViewReportTypeOffensive(): bool
    {
        return $this->isPointOfViewReportTypeOffensive;
    }

    /**
     * @return string
     */
    public function getPointOfViewDisplayName(): string
    {
        return $this->pointOfViewDisplayName;
    }

    /**
     * @return string
     */
    public function getTargetName(): string
    {
        return $this->targetName;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string[]
     */
    public function getReportData(): array
    {
        return $this->reportData;
    }
}
