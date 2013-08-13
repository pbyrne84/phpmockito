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
    }


    /**
     * @return string
     */
    public function renderSignature() {
        $parameterText = $this->getTypeHint( $this->reflectionParameter ) . ' $' . $this->reflectionParameter->getName();
        $defaultValue  = $this->calculateDefaultValue( $this->reflectionParameter );

        $parameterText .= $defaultValue;

        return $parameterText;
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
     * @return bool
     */
    public function isInternal() {
        return $this->reflectionParameter
                ->getDeclaringClass()
                ->isInternal();
    }


    /**
     * @param \ReflectionParameter $parameter
     *
     * @return string
     */
    private function calculateDefaultValue( \ReflectionParameter $parameter ) {
        $defaultValue = '';
        // Internal methods default values are not available so deferring to null
        if ( $this->isInternal() ) {
            $defaultValue = ' = NULL';

            return $defaultValue;
        } else {
            if ( $parameter->isOptional() ) {
                var_dump( $parameter->getDefaultValue() );
                $defaultValue = ' = ' . preg_replace( '~\s~m', '', var_export( $parameter->getDefaultValue(), true ) );

                return $defaultValue;
            }

            return $defaultValue;
        }
    }

}
