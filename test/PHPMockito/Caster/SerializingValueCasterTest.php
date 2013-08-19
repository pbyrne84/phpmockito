<?php
namespace PHPMockito\Caster;

class SerializingValueCasterTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {

    }


    public function test_string() {
        $serializingValueCaster = new VarExportingValueCaster( 'test string' );
        $actualMap              = $this->createComparableMap(
            $serializingValueCaster->getOriginalType(),
            $serializingValueCaster->toPrimitive()
        );

        $this->assertEquals(
            $this->createComparableMap( 'string', "'test string'" ),
            $actualMap
        );
    }


    /**
     * @param string $type
     * @param string $value
     *
     * @return array
     */
    private function createComparableMap( $type, $value ) {
        return array(
            'type'  => $type,
            'value' => $value,
        );
    }


    public function test_stdClass() {
        $stdClass    = new \stdClass();
        $stdClass->a = 'string a';
        $stdClass->b = 'string n';

        $serializingValueCaster = new VarExportingValueCaster( $stdClass );
        $actualMap              = $this->createComparableMap(
            $serializingValueCaster->getOriginalType(),
            $serializingValueCaster->toPrimitive()
        );

        $expectedExport = <<<PHP
stdClass::__set_state(array(
   'a' => 'string a',
   'b' => 'string n',
))
PHP;
        $this->assertEquals(
            $this->createComparableMap( 'stdClass', $expectedExport ),
            $actualMap
        );
    }
}
  
