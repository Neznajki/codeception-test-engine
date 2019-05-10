<?php /** @noinspection PhpUndefinedClassInspection */

/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:21 AM
 */

namespace Tests\Neznajka\Unit\Traits\PhpUnitClass;


use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount as AnyInvokedCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtIndex as InvokedAtIndexMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtLeastCount as InvokedAtLeastCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtLeastOnce as InvokedAtLeastOnceMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtMostCount as InvokedAtMostCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls as ConsecutiveCallsStub;
use PHPUnit\Framework\MockObject\Stub\Exception as ExceptionStub;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument as ReturnArgumentStub;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback as ReturnCallbackStub;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf as ReturnSelfStub;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\MockObject\Stub\ReturnValueMap as ReturnValueMapStub;
use PHPUnit\Framework\TestResult;
use Prophecy\Prophecy\ObjectProphecy;
use SebastianBergmann\Comparator\Comparator;
use Throwable;

/**
 * Class TestCaseTrait
 * @package Tests\Neznajka\Unit\Traits\CodeceptionClass
 * @uses TestCase
 *
 *    
 * @property $backupGlobals;
 * @property $backupGlobalsBlacklist;
 * @property $backupStaticAttributes;
 * @property $backupStaticAttributesBlacklist;
 * @property $runTestInSeparateProcess;
 * @property $preserveGlobalState;
 *
 * @method AnyInvokedCountMatcher any()
 * @method InvokedCountMatcher never()
 * @method InvokedAtLeastCountMatcher atLeast(int $requiredInvocations)
 * @method InvokedAtLeastOnceMatcher atLeastOnce()
 * @method InvokedCountMatcher once()
 * @method InvokedCountMatcher exactly(int $count)
 * @method InvokedAtMostCountMatcher atMost(int $allowedInvocations)
 * @method InvokedAtIndexMatcher at(int $index)
 * @method ReturnStub returnValue($value)
 * @method ReturnValueMapStub returnValueMap(array $valueMap)
 * @method ReturnArgumentStub returnArgument(int $argumentIndex)
 * @method ReturnCallbackStub returnCallback($callback)
 * @method ReturnSelfStub returnSelf()
 * @method ExceptionStub throwException(Throwable $exception)
 * @method ConsecutiveCallsStub onConsecutiveCalls(...$args)
 * @method __construct($name = null, array $data = [], $dataName = '');
 * @method setUpBeforeClass();
 * @method tearDownAfterClass();
 * @method setUp();
 * @method tearDown();
 * @method string toString()
 * @method int count()
 * @method array getGroups()
 * @method setGroups(array $groups);
 * @method array getAnnotations()
 * @method string|null getName(bool $withDataSet = true);
 * @method int getSize()
 * @method bool hasSize()
 * @method bool isSmall()
 * @method bool isMedium()
 * @method bool isLarge()
 * @method string getActualOutput()
 * @method bool hasOutput()
 * @method bool doesNotPerformAssertions()
 * @method expectOutputRegex(string $expectedRegex);
 * @method expectOutputString(string $expectedString);
 * @method bool hasExpectationOnOutput()
 * @method string|null getExpectedException();
 * @method getExpectedExceptionCode();
 * @method string getExpectedExceptionMessage()
 * @method string getExpectedExceptionMessageRegExp()
 * @method expectException(string $exception);
 * @method expectExceptionCode($code);
 * @method expectExceptionMessage(string $message);
 * @method expectExceptionMessageRegExp(string $messageRegExp);
 * @method expectExceptionObject(\Exception $exception);
 * @method expectNotToPerformAssertions();
 * @method setRegisterMockObjectsFromTestArgumentsRecursively(bool $flag);
 * @method setUseErrorHandler(bool $useErrorHandler);
 * @method int getStatus()
 * @method markAsRisky();
 * @method string getStatusMessage()
 * @method bool hasFailed()
 * @method TestResult run(TestResult $result = null)
 * @method runBare();
 * @method setName(string $name);
 * @method setDependencies(array $dependencies);
 * @method array getDependencies()
 * @method bool hasDependencies()
 * @method setDependencyInput(array $dependencyInput);
 * @method setBeStrictAboutChangesToGlobalState(?bool $beStrictAboutChangesToGlobalState);
 * @method setBackupGlobals(?bool $backupGlobals);
 * @method setBackupStaticAttributes(?bool $backupStaticAttributes);
 * @method setRunTestInSeparateProcess(bool $runTestInSeparateProcess);
 * @method setRunClassInSeparateProcess(bool $runClassInSeparateProcess);
 * @method setPreserveGlobalState(bool $preserveGlobalState);
 * @method setInIsolation(bool $inIsolation);
 * @method bool isInIsolation()
 * @method getResult();
 * @method setResult($result);
 * @method setOutputCallback(callable $callback);
 * @method getTestResultObject(): ?TestResult;
 * @method setTestResultObject(TestResult $result);
 * @method registerMockObject(MockObject $mockObject);
 * @method MockBuilder getMockBuilder($className)
 * @method addToAssertionCount(int $count);
 * @method int getNumAssertions()
 * @method bool usesDataProvider()
 * @method string dataDescription()
 * @method dataName();
 * @method registerComparator(Comparator $comparator);
 * @method string getDataSetAsString(bool $includeData = true)
 * @method array getProvidedData()
 * @method addWarning(string $warning);
 * @method runTest();
 * @method iniSet(string $varName, $newValue);
 * @method setLocale(...$args);
 * @method MockObject createMock($originalClassName)
 * @method MockObject createConfiguredMock($originalClassName, array $configuration)
 * @method MockObject createPartialMock($originalClassName, array $methods)
 * @method MockObject createTestProxy(string $originalClassName, array $constructorArguments = [])
 * @method string getMockClass($originalClassName, $methods = [], array $arguments = [], $mockClassName = '', $callOriginalConstructor = false, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false)
 * @method MockObject getMockForAbstractClass($originalClassName, array $arguments = [], $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = [], $cloneArguments = false)
 * @method MockObject getMockFromWsdl($wsdlFile, $originalClassName = '', $mockClassName = '', array $methods = [], $callOriginalConstructor = true, array $options = [])
 * @method MockObject getMockForTrait($traitName, array $arguments = [], $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $mockedMethods = [], $cloneArguments = false)
 * @method getObjectForTrait($traitName, array $arguments = [], $traitClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true);
 * @method ObjectProphecy prophesize($classOrInterface = null)
 * @method TestResult createResult()
 * @method assertPreConditions();
 * @method assertPostConditions();
 * @method onNotSuccessfulTest(Throwable $t);
 */
trait TestCaseTrait
{
    use AssertTrait;
}
