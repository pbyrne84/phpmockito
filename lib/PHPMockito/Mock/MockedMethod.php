<?php

namespace PHPMockito\Mock;

class MockedMethod {
    const CLASS_NAME = __CLASS__;

    /** @var \ReflectionMethod */
    private $reflectionMethod;

    /** @var array|MockedParameter[] */
    private $mockedParameters = array();


    /**
     * @param \ReflectionMethod $reflectionMethod
     */
    function __construct( \ReflectionMethod $reflectionMethod ) {
        $this->reflectionMethod = $reflectionMethod;
        foreach ( $this->reflectionMethod->getParameters() as $parameter ) {
            $this->mockedParameters[ ] = new MockedParameter( $parameter );
        }
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->reflectionMethod->getName();
    }


    /**
     * @return string
     */
    public function getSignature() {
        $parameterTextList = array();
        foreach ( $this->mockedParameters as $parameter ) {
            $parameterTextList[ ] = $parameter->renderSignature();
        }

        return trim( implode( ', ', $parameterTextList ) );
    }


    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getVisibilityAsString() {
        if ( $this->reflectionMethod->isPublic() ) {
            return 'public';
        } elseif ( $this->reflectionMethod->isProtected() ) {
            return 'protected';
        } elseif ( $this->reflectionMethod->isPrivate() ) {
            throw new \UnexpectedValueException( 'private methods cannot be mocked' );
        }

        return '';
    }


    /**
     * @return bool
     */
    public function isInternal() {
        return $this->reflectionMethod
                ->getDeclaringClass()
                ->isInternal();
    }


}
