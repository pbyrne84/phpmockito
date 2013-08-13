<?php
namespace PHPMockito\Mock;

class MockFactory {
    const CLASS_NAME = __CLASS__;

    private $mockCounter = 0;


    function __construct() {
    }


    /**
     * @param string $className
     * @return object
     */
    public function newFullMock( $className ) {
        return $this->createMock( new \ReflectionClass( $className ), array() );
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @param array            $mockedMethodList
     *
     * @return object
     */
    private function createMock( \ReflectionClass $reflectionClass, array $mockedMethodList ) {
        $mockShortClassName     = $reflectionClass->getShortName() . '_Mock_' . $this->mockCounter++;
        $namespace              = $reflectionClass->getNamespaceName();
        $mockFullyQualifiedName = $namespace . '\\' . $mockShortClassName;

        $mockCode = <<<TEXT
namespace {$namespace}{

    class $mockShortClassName extends {$reflectionClass->getShortName()}{

    }
}
TEXT;

        eval( $mockCode );
        return new $mockFullyQualifiedName;

    }
}
