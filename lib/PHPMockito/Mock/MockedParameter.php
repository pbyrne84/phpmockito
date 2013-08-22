<?php

namespace PHPMockito\Mock;

class MockedParameter {
    const CLASS_NAME = __CLASS__;

    /** @var \ReflectionParameter */
    private $reflectionParameter;


    /**
     * @param \ReflectionParameter $reflectionParameter
     */
    function __construct( \ReflectionParameter $reflectionParameter ) {
        $this->reflectionParameter = $reflectionParameter;

        $parameterText = $this->getTypeHint( $this->reflectionParameter ) . ' $' . $this->reflectionParameter->getName();
        $defaultValue  = $this->calculateDefaultValue();

        $parameterText .= $defaultValue;

        $this->parameterText = $parameterText;
    }


    /**
     * @param \ReflectionParameter $parameter
     *
     * @return string
     */
    private function getTypeHint( \ReflectionParameter $parameter ) {
        if ( $parameter->getClass() ) {
            return '\\' . $parameter
                    ->getClass()
                    ->getName();
        }

        if ( $parameter->isArray() ) {
            return 'array';
        }

        return '';
    }


    /**
     * @return string
     */
    private function calculateDefaultValue() {
        if ( !$this->isOptionalValue() ) {
            return '';
        }

        return ' = ' . $this->getDefaultValue();
    }


    /**
     * @return bool
     */
    public function isOptionalValue() {
        if ( $this->isInternal() ) {
            return true;
        }

        return $this->reflectionParameter->isOptional();
    }


    /**
     * @return bool
     */
    public function isInternal() {
        return $this->reflectionParameter
                ->getDeclaringClass()
                ->isInternal();
    }


    /**
     * @return string
     * @throws \BadMethodCallException
     */
    public function getDefaultValue() {
        if ( !$this->isOptionalValue() ) {
            throw new \BadMethodCallException( "Parameter is not optional" );
        }

        if ( $this->isInternal() ) {
            return 'null';
        }

        return preg_replace( '~\s~m', '', var_export( $this->reflectionParameter->getDefaultValue(), true ) );
    }


    /**
     * @return string
     */
    public function renderSignature() {
        return $this->parameterText;
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->reflectionParameter->getName();
    }

}
