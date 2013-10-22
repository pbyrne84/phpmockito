<?php
namespace PHPMockito\Mock;


use PHPMockito\Action\MethodCallListenerFactory;
use PHPMockito\Mock\Logger\MockedClassCodeLogger;
use PHPMockito\Mock\Method\MethodCodeGenerator;
use PHPMockito\Mock\Method\MockMethodCodeGenerator;
use PHPMockito\Mock\Method\SpyMethodCodeGenerator;
use PHPMockito\ToString\ToStringAdaptorFactory;

class MockFactory {
    const CLASS_NAME = __CLASS__;

    private $mockCounter = 0;

    /** @var MockClassCodeGenerator */
    private $mockClassCodeGenerator;

    /** @var MethodCallListenerFactory */
    private $methodCallListenerFactory;

    /** @var MockedMethodListFactory */
    private $mockedMethodListFactory;

    /** @var Logger\MockedClassCodeLogger */
    private $mockedClassCodeLogger;

    /** @var \PHPMockito\ToString\ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param MockClassCodeGenerator       $mockClassCodeGenerator
     * @param MethodCallListenerFactory    $methodCallListenerFactory
     * @param MockedMethodListFactory      $mockedMethodListFactory
     * @param Logger\MockedClassCodeLogger $mockedClassCodeLogger
     * @param ToStringAdaptorFactory       $toStringAdaptorFactory
     */
    function __construct( MockClassCodeGenerator $mockClassCodeGenerator,
                          MethodCallListenerFactory $methodCallListenerFactory,
                          MockedMethodListFactory $mockedMethodListFactory,
                          MockedClassCodeLogger $mockedClassCodeLogger,
                          ToStringAdaptorFactory $toStringAdaptorFactory ) {
        $this->mockClassCodeGenerator    = $mockClassCodeGenerator;
        $this->methodCallListenerFactory = $methodCallListenerFactory;
        $this->mockedMethodListFactory   = $mockedMethodListFactory;
        $this->mockedClassCodeLogger     = $mockedClassCodeLogger;
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    /**
     * @param string $className
     *
     * @return object
     */
    public function mock( $className ) {
        $reflectionClass = new \ReflectionClass( $className );

        $mockedMethods = $this->mockedMethodListFactory->createPublicListFromReflectionClass( $reflectionClass );

        return $this->createMock(
            new MockMethodCodeGenerator(),
            $reflectionClass,
            $mockedMethods
        );
    }


    /**
     * @param Method\MethodCodeGenerator $methodCodeGenerator
     * @param \ReflectionClass           $reflectionClass
     * @param array|MockedMethod[]       $mockedMethodList
     *
     * @param string                     $prefix
     *
     * @return object
     */
    private function createMock( MethodCodeGenerator $methodCodeGenerator,
                                 \ReflectionClass $reflectionClass,
                                 array $mockedMethodList,
                                 $prefix = '' ) {
        $this->mockCounter++;

        $mockShortClassName     = $prefix . $reflectionClass->getShortName() . '_PhpMockitoMock';
        $namespace              = $reflectionClass->getNamespaceName();
        $mockFullyQualifiedName = $namespace . '\\' . $mockShortClassName;

        $mockCode = $this->mockClassCodeGenerator->createMockCode(
            $mockShortClassName,
            $reflectionClass,
            $methodCodeGenerator,
            $mockedMethodList
        );

        if ( class_exists( $mockFullyQualifiedName, false ) ) {
            return new $mockFullyQualifiedName( $this->createMockedClassConstructorParams() );
        }

        $this->mockedClassCodeLogger->logMockCode( $mockFullyQualifiedName, $mockCode );
        eval( $mockCode );

        return new $mockFullyQualifiedName( $this->createMockedClassConstructorParams() );

    }


    /**
     * @return MockedClassConstructorParams
     */
    private function createMockedClassConstructorParams() {
        return new MockedClassConstructorParams(
            $this->toStringAdaptorFactory,
            'mock_instance_' . $this->mockCounter,
            $this->methodCallListenerFactory->createMethodCallListener()
        );
    }


    /**
     * @param $className
     *
     * @return object
     */
    public function spy( $className ) {
        $reflectionClass = new \ReflectionClass( $className );

        return $this->createMock(
            new SpyMethodCodeGenerator(),
            $reflectionClass,
            $this->mockedMethodListFactory->createProtectedAndPublicListFromReflectionClass( $reflectionClass ),
            'Spy_'
        );
    }


}
