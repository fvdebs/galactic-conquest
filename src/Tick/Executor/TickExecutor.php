<?php

declare(strict_types=1);

namespace GC\Tick\Executor;

use Doctrine\ORM\EntityManagerInterface;
use GC\Tick\Exception\UniverseNotFoundException;
use GC\Tick\Plugin\TickPluginResult;
use GC\Universe\Model\Universe;
use GC\Universe\Model\UniverseRepository;
use Throwable;

use function round;
use function microtime;

final class TickExecutor implements TickExecutorInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \GC\Tick\Plugin\TickPluginInterface[]
     */
    private $tickPlugins;

    /**
     * @var \GC\Universe\Model\UniverseRepository
     */
    private $universeRepository;

    /**
     * @param \GC\Tick\Plugin\TickPluginInterface[] $tickPlugins
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     */
    public function __construct(
        array $tickPlugins,
        EntityManagerInterface $entityManager,
        UniverseRepository $universeRepository
    ) {
        $this->tickPlugins = $tickPlugins;
        $this->entityManager = $entityManager;
        $this->universeRepository = $universeRepository;
    }

    /**
     * @param int $universeId
     * @param bool $force - default: false
     *
     * @return bool
     */
    public function isCalculationNeeded(int $universeId, bool $force = false): bool
    {
        return $force || $this->findUniverseById($universeId)->isTickCalculationNeeded();
    }

    /**
     * @param int $universeId
     *
     * @return \GC\Tick\Executor\TickExecutorResultInterface
     */
    public function calculate(int $universeId): TickExecutorResultInterface
    {
        $universe = $this->findUniverseById($universeId);
        if ($universe === null) {
            throw UniverseNotFoundException::fromUniverseId($universeId);
        }

        $executorResult = new TickExecutorResult($universe);
        $tickPluginResult = null;

        try {
            $this->entityManager->getConnection()->beginTransaction();

            foreach ($this->tickPlugins as $tickPlugin) {

                $tickPluginResult = new TickPluginResult();
                $tickPluginResult->setPluginClass(get_class($tickPlugin));

                $executorResult->addPluginResult($tickPluginResult);

                $start = $this->startMicroTime();

                $tickPlugin->executeFor($universe, $tickPluginResult);

                $this->entityManager->flush();

                $tickPluginResult->setTime($this->endMicroTime($start));
            }

            $this->entityManager->commit();

        } catch (Throwable $throwable) {
            $this->entityManager->rollback();
            if ($tickPluginResult !== null) {
                $tickPluginResult->setException($throwable);
                $tickPluginResult->setSuccessful(false);

                return $executorResult;
            }

            echo $throwable->getMessage();
            throw $throwable;
        }

        return $executorResult;
    }

    /**
     * @return \GC\Universe\Model\Universe[]
     *
     * @return void
     */
    public function findUniversesWhichNeedsCalculation(): array
    {
        return $this->universeRepository->findStartedAndActiveUniverses();
    }

    /**
     * @param int $universeId
     *
     * @return \GC\Universe\Model\Universe
     */
    private function findUniverseById(int $universeId): Universe
    {
        return $this->universeRepository->findById($universeId);
    }

    /**
     * @return float
     */
    private function startMicroTime(): float
    {
        return microtime(true);
    }

    /**
     * @param float $start
     *
     * @return float
     */
    private function endMicroTime(float $start): float
    {
        return round(microtime(true) - $start, 2);
    }
}
