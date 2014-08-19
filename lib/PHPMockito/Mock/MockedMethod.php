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
     * @return bool
     */
    public function returnsReference() {
        return $this->reflectionMethod->returnsReference();
    }


    /**
     * @return string
     */
    public function getReturnsReferenceSignature() {
        return $this->returnsReference()
                ? '&'
                : '';
    }


    /**
     * @return string
     */
    public function getSignature() {
        $parameterTextList = array();
        foreach ( $this->mockedParameters as $parameter ) {
            $parameterTextList[ ] = $parameter->getTextSignature();
        }

        return trim( implode( ', ', $parameterTextList ) );
    }


    /**
     * @return string
     */
    public function getCommaSeparatedArguments() {
        $parameterTextList = array();
        foreach ( $this->mockedParameters as $parameter ) {
            $parameterTextList[ ] = '$' . $parameter->getName();
        }

        return trim( implode( ', ', $parameterTextList ) );
    }


    /**
     * @return string
     */
    public function getParameterArrayEntrapment() {
        $parameterTextList = array();
        foreach ( $this->mockedParameters as $parameter ) {
            $parameterTextList[ ] = '$' . $parameter->getName();
        }

        $passedParameterListString = 'array(' . trim( implode( ', ', $parameterTextList ) ) . ')';

        return 'count(' . $passedParameterListString . ') < count( func_get_args() ) ? func_get_args() :  ' .
               $passedParameterListString;
    }


    /**
     * @return array
     */
    public function getOptionalArgumentMap() {
        $optionArgumentMap = array();
        foreach ( $this->mockedParameters as $parameterIndex => $mockedParameter ) {
            if ( $mockedParameter->isOptionalValue() ) {
                $optionArgumentMap[ $parameterIndex ] = $mockedParameter->getDefaultValue();
            }
        }

        return $optionArgumentMap;
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

}
