<?php
namespace PHPMockito\Mock;

class MockFactory {
    const CLASS_NAME = __CLASS__;

    private $mockCounter = 0;

    /** @var MockClassCodeGenerator */
    private $mockClassCodeGenerator;


    /**
     * @param MockClassCodeGenerator $mockClassCodeGenerator
     */
    function __construct( MockClassCodeGenerator $mockClassCodeGenerator ) {
        $this->mockClassCodeGenerator = $mockClassCodeGenerator;
    }


    /**
     * @param string $className
     *
     * @return object
     */
    public function newFullMock( $className ) {
        return $this->createMock( new \ReflectionClass( $className ), array() );
    }


    /**
     * @param \ReflectionClass        $reflectionClass
     * @param array|MockedParameter[] $mockedMethodList
     *
     * @return object
     */
    private function createMock( \ReflectionClass $reflectionClass, array $mockedMethodList ) {
        $mockShortClassName     = $reflectionClass->getShortName() . '_Mock_' . $this->mockCounter++;
        $namespace              = $reflectionClass->getNamespaceName();
        $mockFullyQualifiedName = $namespace . '\\' . $mockShortClassName;

        $mockCode = $this->mockClassCodeGenerator->createMockCode(
            $mockShortClassName,
            $reflectionClass,
            $mockedMethodList
        );

        eval( $mockCode );

        return new $mockFullyQualifiedName;

    }
}
