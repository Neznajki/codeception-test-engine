<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 3:35 PM
 */

namespace Tests\TestsEngine\unit\Extension;

use AspectMock\Core\Registry;
use AspectMock\Kernel;
use AspectMock\Test;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractSimpleCodeceptionTest;
use Tests\Neznajka\Codeception\Engine\Extension\StaticUnitTestsExtension;

/**
 * Class StaticUnitTestsExtensionTest
 * @package Tests\TestsEngine\unit\Extension
 *
 * @method StaticUnitTestsExtension|MockObject getWorkingClass(... $mockedMethods)
 */
class StaticUnitTestsExtensionTest extends AbstractSimpleCodeceptionTest
{
    public function test_eventDefinition()
    {
        $data = StaticUnitTestsExtension::$events;

        $this->assertArrayHasKey(Events::SUITE_INIT, $data);
        $this->assertArrayHasKey(Events::SUITE_AFTER, $data);
        $this->assertArrayHasKey(Events::TEST_AFTER, $data);

        $this->assertEquals(
            [
                ['init'],
                ['receiveModuleContainer'],
            ],
            $data[Events::SUITE_INIT]
        );
        $this->assertEquals(
            'cleanup',
            $data[Events::SUITE_AFTER]
        );
        $this->assertEquals(
            'clearStatic',
            $data[Events::TEST_AFTER]

        );
    }

    public function test_init()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass(
            'getIncludePath',
            'getAspectKernel',
            'setSuiteEvent',
            'getProjectDir',
            'getCurrentProcessRunDir',
            'setCacheFolder',
            'createTempFolder',
            'getCacheFolder'
        );

        $mockedKernel = $this->createMockExpectsOnlyMethodUsage(
            Kernel::class,
            [
                'init',
            ]
        );
        /** @var MockObject|SuiteEvent $eventMock */
        $eventMock = $this->createMockExpectsNoUsage(SuiteEvent::class);

        $projectDirMock  = $this->getString();
        $runDirMock      = $this->getString();
        $tempFolderMock  = $this->getString();
        $includePathMock = $this->getArray();

        $workingClass->expects($this->once())->method('setSuiteEvent')->with($eventMock)->willReturn($workingClass);
        $workingClass->expects($this->exactly(2))->method('getProjectDir')->with()->willReturn($projectDirMock);
        $workingClass->expects($this->once())->method('getCurrentProcessRunDir')->with()->willReturn($runDirMock);
        $workingClass->expects($this->once())->method('setCacheFolder')->with($runDirMock);
        $workingClass->expects($this->once())->method('createTempFolder')->with();
        $workingClass->expects($this->once())->method('getAspectKernel')->with()->willReturn($mockedKernel);
        $workingClass->expects($this->once())->method('getIncludePath')->with()->willReturn($includePathMock);
        $workingClass->expects($this->once())->method('getCacheFolder')->with()->willReturn($tempFolderMock);
        $mockedKernel->expects($this->once())->method('init')->with(
            [
                'debug'        => true,
                'cacheDir'     => $tempFolderMock,
                'includePaths' => $includePathMock,
                'excludePaths' => [$projectDirMock],
            ]
        );

        $workingClass->init($eventMock);
    }

    public function test_cleanup()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass('getCacheFolder');

        /** @var MockObject|SuiteEvent $eventMock */
        $eventMock       = $this->createMockExpectsNoUsage(SuiteEvent::class);
        $cacheFolderMock = $this->getString();
//        $functionMock    = Test::func(
//            $this->getWorkingClassNameSpace(),
//            'exec',
//            function () {
//                return true;
//            }
//        );

        $workingClass->expects($this->once())->method('getCacheFolder')->with()->willReturn($cacheFolderMock);

        $workingClass->cleanup($eventMock);

//        $functionMock->verifyInvokedOnce(['rm -rf ' . $cacheFolderMock]);//conflicts op cache and functional tests

//        Test::func(
//            $this->getWorkingClassNameSpace(),
//            'exec',
//            function () {
//                call_user_func_array('exec', func_get_args());
//            }
//        );
    }

    public function test_clearStatic()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass();

        Test::func(__NAMESPACE__, 'file', true);
        /** @var MockObject|TestEvent $eventMock */
        $eventMock = $this->createMockExpectsNoUsage(TestEvent::class);

        file('');
        $workingClass->clearStatic($eventMock);

        $this->assertEquals([], Registry::getFuncCallsFor(__NAMESPACE__ . '\\file'));
        $this->assertEquals([], $this->getNotPublicValue(Registry::$mocker, 'funcMap'));
    }

    public function test_getProjectDir()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClassPrivateMock();

        $expectingResult = $this->getString();

        Test::func($this->getWorkingClassNameSpace(), 'md5', $expectingResult);
        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_getIncludePath()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass('getAspectIncludePath', 'getIncludePathEntry');

        $singleEntryMock       = $this->getString();
        $aspectIncludePathMock = [
            $singleEntryMock,
        ];

        $includePathEntry = $this->getString();
        $expectedResult   = [
            $singleEntryMock => $includePathEntry,
        ];

        $workingClass->expects($this->once())->method('getAspectIncludePath')->with()->willReturn($aspectIncludePathMock);
        $workingClass->expects($this->once())->method('getIncludePathEntry')->with($singleEntryMock)->willReturn(
            $includePathEntry
        );

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());

        $this->assertEquals($expectedResult, $result);
    }

    public function test_getProjectRoot()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass('constructProjectRootDir');

        $expectingResult = $projectRootDirMock = $this->getString();
        $workingClass->expects($this->once())->method('constructProjectRootDir')->with()->willReturn($projectRootDirMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_constructProjectRootDir_case_success()
    {
        $this->wantToTestThisMethod();

        $workingClass    = $this->getWorkingClass();
        $expectingResult = dirname(dirname(dirname(__DIR__)));

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_constructProjectRootDir_case_exception()
    {
        $this->wantToTestThisMethod();

        $this->expectException(LogicException::class);
        $workingClass    = $this->getWorkingClass();
        $expectingResult = dirname(dirname(dirname(__DIR__)));

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), '/I/have/no/vendor');
        $this->assertEquals($expectingResult, $result);
    }

    public function test_suiteEvent()
    {
        $this->executeGetterSetterTest('suiteEvent', $this->createMockExpectsNoUsage(SuiteEvent::class));
    }

    public function test_getTempFolder_case_success()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass('getAspectMockConfiguration');

        $expectingResult   = $this->getString();
        $configurationMock = ['temp_folder' => $expectingResult, 'gg' => 'tt'];

        $workingClass->expects($this->once())->method('getAspectMockConfiguration')->with()->willReturn($configurationMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_getTempFolder_case_exception()
    {
        $this->wantToTestThisMethod();
        $this->expectException(LogicException::class);
        $workingClass = $this->getWorkingClass('getAspectMockConfiguration');

        $expectingResult   = $this->getString();
        $configurationMock = [$this->getString() => $expectingResult, 'gg' => 'tt'];

        $workingClass->expects($this->once())->method('getAspectMockConfiguration')->with()->willReturn($configurationMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
    }

    public function test_getAspectIncludePath_case_success()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getAspectMockConfiguration');

        $expectingResult   = $this->getArray();
        $configurationMock = ['include_paths' => $expectingResult, 'gg' => 'tt'];

        $workingClass->expects($this->once())->method('getAspectMockConfiguration')->with()->willReturn($configurationMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_getAspectIncludePath_case_exception()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getAspectMockConfiguration');

        $expectingResult   = $this->getArray();
        $configurationMock = [$this->getString() => $expectingResult, 'gg' => 'tt'];

        $workingClass->expects($this->once())->method('getAspectMockConfiguration')->with()->willReturn($configurationMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals([], $result);
    }

    public function test_getAspectMockConfiguration_case_success()
    {
        $this->wantToTestThisMethod();
        $workingClass = $this->getWorkingClass('getSuiteEvent');

        $eventMock = $this->createMockExpectsOnlyMethodUsage(SuiteEvent::class, ['getSettings']);

        $expectingResult = $this->getArray();
        $settingsMock    = ['aspect_mock' => $expectingResult, 'gg' => 'tt'];
        $eventMock->expects($this->once())->method('getSettings')->with()->willReturn($settingsMock);

        $workingClass->expects($this->once())->method('getSuiteEvent')->with()->willReturn($eventMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
        $this->assertEquals($expectingResult, $result);
    }

    public function test_getAspectMockConfiguration_case_exception()
    {
        $this->wantToTestThisMethod();
        $this->expectException(LogicException::class);
        $workingClass = $this->getWorkingClass('getSuiteEvent');

        $eventMock = $this->createMockExpectsOnlyMethodUsage(SuiteEvent::class, ['getSettings']);

        $expectingResult = $this->getArray();
        $settingsMock    = [$this->getString() => $expectingResult, 'gg' => 'tt'];
        $eventMock->expects($this->once())->method('getSettings')->with()->willReturn($settingsMock);

        $workingClass->expects($this->once())->method('getSuiteEvent')->with()->willReturn($eventMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());
    }

    public function test_getAspectKernel()
    {
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass();

        $expectingResult = Kernel::getInstance();
        $result          = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName());

        $this->assertSame($expectingResult, $result);
    }

    public function test_getCurrentProcessRunDir()
    {
        return 'can not be executed (method is cached and you can not rewrite uniqid)';
    }

    public function test_createTempFolder()
    {
        return 'can not be executed (method is cached and you can not rewrite mkdir)';
    }

    public function test_cacheFolder()
    {
        $this->setKeepExistingCodeFunctions(
            [
                'setCacheFolder',
                'getCacheFolder',
            ]
        );
        $this->wantToTestThisMethod();

        $workingClass = $this->getWorkingClass('getTempFolder');

        $projectDirMock  = $this->getString();
        $processIdMock   = $this->getString();
        $expectingResult = sys_get_temp_dir() . "/{$projectDirMock}/{$processIdMock}";

        $workingClass->expects($this->once())->method('getTempFolder')->with()->willReturn($projectDirMock);

        $result = $this->runNotPublicMethod($workingClass, 'setCacheFolder', $processIdMock);
        $this->assertEquals($workingClass, $result);

        $result = $this->runNotPublicMethod($workingClass, 'getCacheFolder', $processIdMock);
        $this->assertEquals($expectingResult, $result);
    }

    public function test_getIncludePathEntry()
    {
        $this->wantToTestThisMethod();

        //success
        $workingClass = $this->getWorkingClass('getProjectRoot');

        $projectRootMock = __DIR__;
        $fileMock        = 'StaticUnitTestsExtensionTest.php';
        $expectingResult = __DIR__ . '/' . $fileMock;

        $workingClass->expects($this->once())->method('getProjectRoot')->with()->willReturn($projectRootMock);

        $result = $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $fileMock);
        $this->assertSame($expectingResult, $result);


        //exception
        $this->expectException(LogicException::class);
        $workingClass = $this->getWorkingClass('getProjectRoot');

        $projectRootMock = $this->getString();
        $fileMock        = $this->getString();

        $workingClass->expects($this->once())->method('getProjectRoot')->with()->willReturn($projectRootMock);

        $this->runNotPublicMethod($workingClass, $this->getTestingMethodName(), $fileMock);
    }

    /**
     * @return string
     */
    protected function getWorkingClassName(): string
    {
        return StaticUnitTestsExtension::class;
    }
}
