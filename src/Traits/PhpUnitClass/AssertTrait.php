<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 3/1/19
 * Time: 11:43 AM
 */

namespace Tests\Neznajka\Codeception\Engine\Traits\PhpUnitClass;

use PHPUnit\Framework\Assert;
use DOMElement;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Attribute;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\ClassHasAttribute;
use PHPUnit\Framework\Constraint\ClassHasStaticAttribute;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\DirectoryExists;
use PHPUnit\Framework\Constraint\FileExists;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsFinite;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInfinite;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsJson;
use PHPUnit\Framework\Constraint\IsNan;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsReadable;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\IsWritable;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\LogicalXor;
use PHPUnit\Framework\Constraint\ObjectHasAttribute;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringMatchesFormatDescription;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\Constraint\TraversableContains;
use PHPUnit\Framework\Constraint\TraversableContainsOnly;

/**
 * Class AssertTrait
 * @package Tests\Neznajka\Codeception\Engine\Traits\PhpUnitClass
 * @uses Assert
 *
 * @method assertArrayHasKey($key, $array, string $message = '');
 * @method assertArraySubset($subset, $array, bool $checkForObjectIdentity = false, string $message = '');
 * @method assertArrayNotHasKey($key, $array, string $message = '');
 * @method assertContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false);
 * @method assertAttributeContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false);
 * @method assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false);
 * @method assertAttributeNotContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false);
 * @method assertContainsOnly(string $type, iterable $haystack, bool $isNativeType = null, string $message = '');
 * @method assertContainsOnlyInstancesOf(string $className, iterable $haystack, string $message = '');
 * @method assertAttributeContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, bool $isNativeType = null, string $message = '');
 * @method assertNotContainsOnly(string $type, iterable $haystack, bool $isNativeType = null, string $message = '');
 * @method assertAttributeNotContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, bool $isNativeType = null, string $message = '');
 * @method assertCount(int $expectedCount, $haystack, string $message = '');
 * @method assertAttributeCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '');
 * @method assertNotCount(int $expectedCount, $haystack, string $message = '');
 * @method assertAttributeNotCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '');
 * @method assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertEqualsCanonicalizing($expected, $actual, string $message = '');
 * @method assertEqualsIgnoringCase($expected, $actual, string $message = '');
 * @method assertEqualsWithDelta($expected, $actual, float $delta, string $message = '');
 * @method assertAttributeEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertNotEquals($expected, $actual, string $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false);
 * @method assertNotEqualsCanonicalizing($expected, $actual, string $message = '');
 * @method assertNotEqualsIgnoringCase($expected, $actual, string $message = '');
 * @method assertNotEqualsWithDelta($expected, $actual, float $delta, string $message = '');
 * @method assertAttributeNotEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertEmpty($actual, string $message = '');
 * @method assertAttributeEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '');
 * @method assertNotEmpty($actual, string $message = '');
 * @method assertAttributeNotEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '');
 * @method assertGreaterThan($expected, $actual, string $message = '');
 * @method assertAttributeGreaterThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertGreaterThanOrEqual($expected, $actual, string $message = '');
 * @method assertAttributeGreaterThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertLessThan($expected, $actual, string $message = '');
 * @method assertAttributeLessThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertLessThanOrEqual($expected, $actual, string $message = '');
 * @method assertAttributeLessThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertFileEquals(string $expected, string $actual, string $message = '', bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertFileNotEquals(string $expected, string $actual, string $message = '', bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertStringEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertStringNotEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = false, bool $ignoreCase = false);
 * @method assertIsReadable(string $filename, string $message = '');
 * @method assertNotIsReadable(string $filename, string $message = '');
 * @method assertIsWritable(string $filename, string $message = '');
 * @method assertNotIsWritable(string $filename, string $message = '');
 * @method assertDirectoryExists(string $directory, string $message = '');
 * @method assertDirectoryNotExists(string $directory, string $message = '');
 * @method assertDirectoryIsReadable(string $directory, string $message = '');
 * @method assertDirectoryNotIsReadable(string $directory, string $message = '');
 * @method assertDirectoryIsWritable(string $directory, string $message = '');
 * @method assertDirectoryNotIsWritable(string $directory, string $message = '');
 * @method assertFileExists(string $filename, string $message = '');
 * @method assertFileNotExists(string $filename, string $message = '');
 * @method assertFileIsReadable(string $file, string $message = '');
 * @method assertFileNotIsReadable(string $file, string $message = '');
 * @method assertFileIsWritable(string $file, string $message = '');
 * @method assertFileNotIsWritable(string $file, string $message = '');
 * @method assertTrue($condition, string $message = '');
 * @method assertNotTrue($condition, string $message = '');
 * @method assertFalse($condition, string $message = '');
 * @method assertNotFalse($condition, string $message = '');
 * @method assertNull($actual, string $message = '');
 * @method assertNotNull($actual, string $message = '');
 * @method assertFinite($actual, string $message = '');
 * @method assertInfinite($actual, string $message = '');
 * @method assertNan($actual, string $message = '');
 * @method assertClassHasAttribute(string $attributeName, string $className, string $message = '');
 * @method assertClassNotHasAttribute(string $attributeName, string $className, string $message = '');
 * @method assertClassHasStaticAttribute(string $attributeName, string $className, string $message = '');
 * @method assertClassNotHasStaticAttribute(string $attributeName, string $className, string $message = '');
 * @method assertObjectHasAttribute(string $attributeName, $object, string $message = '');
 * @method assertObjectNotHasAttribute(string $attributeName, $object, string $message = '');
 * @method assertSame($expected, $actual, string $message = '');
 * @method assertAttributeSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertNotSame($expected, $actual, string $message = '');
 * @method assertAttributeNotSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '');
 * @method assertInstanceOf(string $expected, $actual, string $message = '');
 * @method assertAttributeInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '');
 * @method assertNotInstanceOf(string $expected, $actual, string $message = '');
 * @method assertAttributeNotInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '');
 * @method assertInternalType(string $expected, $actual, string $message = '');
 * @method assertAttributeInternalType(string $expected, string $attributeName, $classOrObject, string $message = '');
 * @method assertIsArray($actual, string $message = '');
 * @method assertIsBool($actual, string $message = '');
 * @method assertIsFloat($actual, string $message = '');
 * @method assertIsInt($actual, string $message = '');
 * @method assertIsNumeric($actual, string $message = '');
 * @method assertIsObject($actual, string $message = '');
 * @method assertIsResource($actual, string $message = '');
 * @method assertIsString($actual, string $message = '');
 * @method assertIsScalar($actual, string $message = '');
 * @method assertIsCallable($actual, string $message = '');
 * @method assertIsIterable($actual, string $message = '');
 * @method assertNotInternalType(string $expected, $actual, string $message = '');
 * @method assertIsNotArray($actual, string $message = '');
 * @method assertIsNotBool($actual, string $message = '');
 * @method assertIsNotFloat($actual, string $message = '');
 * @method assertIsNotInt($actual, string $message = '');
 * @method assertIsNotNumeric($actual, string $message = '');
 * @method assertIsNotObject($actual, string $message = '');
 * @method assertIsNotResource($actual, string $message = '');
 * @method assertIsNotString($actual, string $message = '');
 * @method assertIsNotScalar($actual, string $message = '');
 * @method assertIsNotCallable($actual, string $message = '');
 * @method assertIsNotIterable($actual, string $message = '');
 * @method assertAttributeNotInternalType(string $expected, string $attributeName, $classOrObject, string $message = '');
 * @method assertRegExp(string $pattern, string $string, string $message = '');
 * @method assertNotRegExp(string $pattern, string $string, string $message = '');
 * @method assertSameSize($expected, $actual, string $message = '');
 * @method assertNotSameSize($expected, $actual, string $message = '');
 * @method assertStringMatchesFormat(string $format, string $string, string $message = '');
 * @method assertStringNotMatchesFormat(string $format, string $string, string $message = '');
 * @method assertStringMatchesFormatFile(string $formatFile, string $string, string $message = '');
 * @method assertStringNotMatchesFormatFile(string $formatFile, string $string, string $message = '');
 * @method assertStringStartsWith(string $prefix, string $string, string $message = '');
 * @method assertStringStartsNotWith($prefix, $string, string $message = '');
 * @method assertStringContainsString(string $needle, string $haystack, string $message = '');
 * @method assertStringContainsStringIgnoringCase(string $needle, string $haystack, string $message = '');
 * @method assertStringNotContainsString(string $needle, string $haystack, string $message = '');
 * @method assertStringNotContainsStringIgnoringCase(string $needle, string $haystack, string $message = '');
 * @method assertStringEndsWith(string $suffix, string $string, string $message = '');
 * @method assertStringEndsNotWith(string $suffix, string $string, string $message = '');
 * @method assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '');
 * @method assertXmlFileNotEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '');
 * @method assertXmlStringEqualsXmlFile(string $expectedFile, $actualXml, string $message = '');
 * @method assertXmlStringNotEqualsXmlFile(string $expectedFile, $actualXml, string $message = '');
 * @method assertXmlStringEqualsXmlString($expectedXml, $actualXml, string $message = '');
 * @method assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, string $message = '');
 * @method assertEqualXMLStructure(DOMElement $expectedElement, DOMElement $actualElement, bool $checkAttributes = false, string $message = '');
 * @method assertThat($value, Constraint $constraint, string $message = '');
 * @method assertJson(string $actualJson, string $message = '');
 * @method assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson, string $message = '');
 * @method assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, string $message = '');
 * @method assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '');
 * @method assertJsonStringNotEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '');
 * @method assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '');
 * @method assertJsonFileNotEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '');
 * @method LogicalAnd logicalAnd();
 * @method LogicalOr logicalOr();
 * @method LogicalNot logicalNot(Constraint $constraint);
 * @method LogicalXor logicalXor();
 * @method IsAnything anything();
 * @method IsTrue isTrue();
 * @method Callback callback(callable $callback);
 * @method IsFalse isFalse();
 * @method IsJson isJson()
 * @method IsNull isNull()
 * @method IsFinite isFinite()
 * @method IsInfinite isInfinite()
 * @method IsNan isNan()
 * @method Attribute attribute(Constraint $constraint, string $attributeName)
 * @method TraversableContains contains($value, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false)
 * @method TraversableContainsOnly containsOnly(string $type)
 * @method TraversableContainsOnly containsOnlyInstancesOf(string $className)
 * @method ArrayHasKey arrayHasKey($key)
 * @method IsEqual equalTo($value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 * @method Attribute attributeEqualTo(string $attributeName, $value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 * @method IsEmpty isEmpty()
 * @method IsWritable isWritable()
 * @method IsReadable isReadable()
 * @method DirectoryExists directoryExists()
 * @method FileExists fileExists()
 * @method GreaterThan greaterThan($value)
 * @method LogicalOr greaterThanOrEqual($value)
 * @method ClassHasAttribute classHasAttribute(string $attributeName)
 * @method ClassHasStaticAttribute classHasStaticAttribute(string $attributeName)
 * @method ObjectHasAttribute objectHasAttribute($attributeName)
 * @method IsIdentical identicalTo($value)
 * @method IsInstanceOf isInstanceOf(string $className)
 * @method IsType isType(string $type)
 * @method LessThan lessThan($value)
 * @method LogicalOr lessThanOrEqual($value)
 * @method RegularExpression matchesRegularExpression(string $pattern)
 * @method StringMatchesFormatDescription matches(string $string)
 * @method StringStartsWith stringStartsWith($prefix)
 * @method StringContains stringContains(string $string, bool $case = true)
 * @method StringEndsWith stringEndsWith(string $suffix)
 * @method Count countOf(int $count)
 * @method fail(string $message = '');
 * @method readAttribute($classOrObject, string $attributeName);
 * @method getStaticAttribute(string $className, string $attributeName);
 * @method getObjectAttribute($object, string $attributeName);
 * @method markTestIncomplete(string $message = '');
 * @method markTestSkipped(string $message = '');
 * @method int getCount()
 * @method resetCount();
 */
trait AssertTrait
{

}
