<?php /** @noinspection PhpUnusedParameterInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 9:48 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Extension;

use AspectMock\Kernel;
use AspectMock\Test;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;
use LogicException;
use RuntimeException;
use Throwable;

/**
 * Class StaticUnitTestsExtension
 * @package Tests\Neznajka\Codeception\Engine\Extension
 */
class StaticUnitTestsExtension extends Extension
{
    /** @var string */
    protected $cacheFolder;
    /** @var string */
    protected $tmpFolder;
    /** @var SuiteEvent */
    protected $suiteEvent;
    /** @var string */
    protected $projectRootDir;

    /**
     * @var array
     */
    public static $events = [
        Events::SUITE_INIT  => 'init',
        Events::SUITE_AFTER => 'cleanup',
        Events::TEST_AFTER  => 'clearStatic',
    ];

    /**
     * @param SuiteEvent $e
     * @throws LogicException
     * @throws RuntimeException
     */
    public function init(SuiteEvent $e)
    {
        $this->setSuiteEvent($e);
        $kernel     = $this->getAspectKernel();
        $projectDir = $this->getProjectDir();
        $runDir     = $this->getCurrentProcessRunDir($projectDir);

        $this->setCacheFolder($runDir);
        $this->createTempFolder();

        $kernel->init(
            [
                'debug'        => true,
                'cacheDir'     => $this->getCacheFolder(),
                'includePaths' => $this->getIncludePath(),
                'excludePaths' => [$this->getProjectDir()],
            ]
        );
    }

    /**
     * @param SuiteEvent $e
     */
    public function cleanup(SuiteEvent $e)
    {
        $command = "rm -rf " . $this->getCacheFolder();

        exec($command);
    }

    /**
     * @param TestEvent $e
     */
    public function clearStatic(TestEvent $e)
    {
        Test::cleanInvocations();
        Test::clean();
    }

    /**
     * @return array
     * @throws LogicException
     */
    protected function getIncludePath(): array
    {
        $result = [];

        foreach ($this->getAspectIncludePath() as $item) {
            $result[$item] = $this->getIncludePathEntry($item);
        }

        return $result;
    }

    /**
     * @return string
     * @throws LogicException
     */
    protected function getProjectRoot(): string
    {
        if ($this->projectRootDir === null) {
            $this->projectRootDir = $this->constructProjectRootDir();
        }

        return $this->projectRootDir;
    }

    /**
     * @param string $next
     * @return string
     * @throws LogicException
     */
    protected function constructProjectRootDir(string $next = null): string
    {
        if ($next === null) {
            $next = __DIR__;
        }
        $current = dirname($next);

        if (file_exists($current . '/vendor')) {
            return $current;
        }

        if (empty($current) || $current === '/') {
            throw new LogicException("project root dir could not be detected");
        }

        return $this->constructProjectRootDir($current);
    }

    /**
     * @return SuiteEvent
     */
    protected function getSuiteEvent(): SuiteEvent
    {
        return $this->suiteEvent;
    }

    /**
     * @return string
     * @throws LogicException
     */
    protected function getTempFolder(): string
    {
        $configuration = $this->getAspectMockConfiguration();

        if (! array_key_exists('temp_folder', $configuration)) {
            throw new LogicException("temp_folder is required for aspect mock static temp file storing");
        }

        return $configuration['temp_folder'];
    }

    /**
     * @return array
     * @throws LogicException
     */
    protected function getAspectIncludePath(): array
    {
        $configuration = $this->getAspectMockConfiguration();

        if (! array_key_exists('include_paths', $configuration)) {
            return [];
        }

        return $configuration['include_paths'];
    }

    /**
     * @return array
     * @throws LogicException
     */
    protected function getAspectMockConfiguration(): array
    {
        $settings = $this->getSuiteEvent()->getSettings();

        if (! array_key_exists('aspect_mock', $settings)) {
            throw new LogicException("aspect_mock is required to detect extension settings");
        }

        return $settings['aspect_mock'];
    }

    /**
     * @param SuiteEvent $suiteEvent
     *
     * @return $this
     */
    protected function setSuiteEvent(SuiteEvent $suiteEvent): self
    {
        $this->suiteEvent = $suiteEvent;

        return $this;
    }

    /**
     * @return Kernel
     */
    protected function getAspectKernel()
    {
        return Kernel::getInstance();
    }

    /**
     * @return string
     * @codeCoverageIgnore mocked as private
     */
    protected function getProjectDir(): string
    {
        return md5(__DIR__);
    }

    /**
     * @param string $projectDir
     * @return string
     * @codeCoverageIgnore mocked as private
     */
    protected function getCurrentProcessRunDir(string $projectDir): string
    {
        return uniqid($projectDir . '_');
    }

    /**
     * @codeCoverageIgnore mocked as private
     * @throws RuntimeException
     */
    protected function createTempFolder()
    {
        try {
            mkdir($this->getCacheFolder(), 0777, true);
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage() . " for path ({$this->getCacheFolder()}");
        }
    }

    /**
     * @param string $runDir
     * @return StaticUnitTestsExtension
     * @throws LogicException
     */
    protected function setCacheFolder(string $runDir): self
    {
        $this->cacheFolder = sys_get_temp_dir() . "/{$this->getTempFolder()}/{$runDir}";

        return $this;
    }

    /**
     * @return string
     */
    protected function getCacheFolder(): string
    {
        return $this->cacheFolder;
    }

    /**
     * @param $item
     * @return string
     * @throws LogicException
     */
    protected function getIncludePathEntry($item): string
    {
        $itemFolder = "{$this->getProjectRoot()}/{$item}";
        if (! file_exists($itemFolder)) {
            throw new LogicException("{$itemFolder} does not exist please remove from aspect mock include path");
        }

        return $itemFolder;
    }
}
