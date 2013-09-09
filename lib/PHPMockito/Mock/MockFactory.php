<?php
namespace PHPMockito\Mock;


use PHPMockito\Action\MethodCallListenerFactory;
use PHPMockito\Mock\Logger\MockedClassCodeLogger;
use PHPMockito\Mock\Method\MockMethodCodeGenerator;

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


    /**
     * @param MockClassCodeGenerator       $mockClassCodeGenerator
     * @param MethodCallListenerFactory    $methodCallListenerFactory
     * @param MockedMethodListFactory      $mockedMethodListFactory
     * @param Logger\MockedClassCodeLogger $mockedClassCodeLogger
     */
    function __construct( MockClassCodeGenerator $mockClassCodeGenerator,
                          MethodCallListenerFactory $methodCallListenerFactory,
                          MockedMethodListFactory $mockedMethodListFactory,
                          MockedClassCodeLogger $mockedClassCodeLogger ) {
        $this->mockClassCodeGenerator    = $mockClassCodeGenerator;
        $this->methodCallListenerFactory = $methodCallListenerFactory;
        $this->mockedMethodListFactory   = $mockedMethodListFactory;
        $this->mockedClassCodeLogger     = $mockedClassCodeLogger;
    }


    /**
     * @param string $className
     *
     * @return object
     */
    public function mock( $className ) {
        $reflectionClass = new \ReflectionClass( $className );

        return $this->createMock(
            $reflectionClass,
            $this->mockedMethodListFactory->createPublicListFromReflectionClass( $reflectionClass )
        );
    }


    /**
     * @param \ReflectionClass     $reflectionClass
     * @param array|MockedMethod[] $mockedMethodList
     *
     * @param string               $prefix
     *
     * @return object
     */
    private function createMock( \ReflectionClass $reflectionClass, array $mockedMethodList, $prefix = '' ) {
        $this->mockCounter++;

        $mockShortClassName     =  $prefix . $reflectionClass->getShortName() . '_PhpMockitoMock';
        $namespace              = $reflectionClass->getNamespaceName();
        $mockFullyQualifiedName = $namespace . '\\' . $mockShortClassName;

        $mockCode = $this->mockClassCodeGenerator->createMockCode(
            $mockShortClassName,
            $reflectionClass,
            new MockMethodCodeGenerator(),
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
            'mock_instance_' . $this->mockCounter,
            $this->methodCallListenerFactory->createMethodCallListener()
        );
    }


    public function spy( $className ) {
        $reflectionClass = new \ReflectionClass( $className );

        return $this->createMock(
            $reflectionClass,
            $this->mockedMethodListFactory->createProtectedAndPublicListFromReflectionClass( $reflectionClass ),
            'Spy_'
        );
    }


}
