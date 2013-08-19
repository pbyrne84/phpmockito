<?php

namespace PHPMockito\Caster;


abstract class ValueCasterTest extends \PHPUnit_Framework_TestCase{
    const CLASS_NAME = __CLASS__;


    /**
     * @param string $type
     * @param string $value
     *
     * @return array
     */
    protected function createComparableMap( $type, $value ) {
        return array(
            'type'  => $type,
            'value' => $value,
        );
    }


}
 