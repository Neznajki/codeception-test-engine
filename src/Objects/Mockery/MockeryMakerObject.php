<?php
/**
 * Created by PhpStorm.
 * User: mlocmelis
 * Date: 9/12/18
 * Time: 1:23 PM
 */

namespace Tests\Neznajka\Unit\Objects\Mockery;


/**
 * Class MockeryMakerObject
 * @package Tests\Neznajka\Unit\Objects
 */
class MockeryMakerObject
{
    /** @var string */
    protected $factoryClassName;
    /** @var string */
    protected $factoryGetterMethod;
    /** @var string */
    protected $factoryConstructorMethod;
    /** @var string */
    protected $resultClassName;
    /** @var array */
    protected $factoryFunctionCallParameters;

    /**
     * MockeryMakerObject constructor.
     * @param string $resultClassName
     * @param string $factoryGetterMethod
     * @param string $factoryClassName
     * @param string $factoryConstructorMethod
     */
    public function __construct(
        string $resultClassName,
        string $factoryGetterMethod = 'getApp',
        string $factoryClassName = 'App\Classes\Application',
        string $factoryConstructorMethod = 'make'
    ) {
        $this->factoryClassName = $factoryClassName;
        $this->factoryGetterMethod = $factoryGetterMethod;
        $this->factoryConstructorMethod = $factoryConstructorMethod;
        $this->resultClassName = $resultClassName;

        $this->setFactoryFunctionCallParameters([$resultClassName]);
    }

    /**
     * @return string
     */
    public function getFactoryClassName(): string
    {
        return $this->factoryClassName;
    }

    /**
     * @return string
     */
    public function getFactoryGetterMethod(): string
    {
        return $this->factoryGetterMethod;
    }

    /**
     * @return string
     */
    public function getFactoryConstructorMethod(): string
    {
        return $this->factoryConstructorMethod;
    }

    /**
     * @return string
     */
    public function getResultClassName(): string
    {
        return $this->resultClassName;
    }

    /**
     * @return array
     */
    public function getFactoryFunctionCallParameters(): array
    {
        return $this->factoryFunctionCallParameters;
    }

    /**
     * @param array $factoryFunctionCallParameters
     * @return static
     */
    public function setFactoryFunctionCallParameters(array $factoryFunctionCallParameters)
    {
        $this->factoryFunctionCallParameters = $factoryFunctionCallParameters;

        return $this;
    }
}
