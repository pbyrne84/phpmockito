<?php
namespace PHPMockito\Expectancy;

class PhpUnitTestCaseInitialisationMatcherTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;

    /**
     * @var PhpUnitTestCaseInitialisationMatcher
     */
    private $phpUnitTestCaseInitialisationMatcher;


    protected function setUp() {
        $this->phpUnitTestCaseInitialisationMatcher = new PhpUnitTestCaseInitialisationMatcher();
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_checkIsInitialisationCall_throwsExceptionOnEmptyBackTrace() {
        $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall( array() );
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_checkIsInitialisationCall_throwsExceptionIfClassIsNotFound() {
        $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall(
            array( array( 'function' => 'functionName' ) )
        );
    }


    public function test_checkIsInitialisationCall_matchesAsADescendantOfPhpUnitIsSecond() {
        $backTrace = array(
            $this->createBacktraceItem( '\DomDocument', __METHOD__ ),
            $this->createBacktraceItem( self::CLASS_NAME, __METHOD__ ),
            $this->createBacktraceItem( PhpUnitTestCaseInitialisationMatcher::CLASS_NAME, 'isInitialisationCall' ),
        );

        $this->assertTrue(
            $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall( $backTrace )
        );
    }


    private function createBacktraceItem( $class, $method ) {
        return array(
            'function' => $method,
            'class'    => $class
        );
    }


    public function test_checkIsInitialisationCall_doesNotMatchAsPhpUnitIsNotSecond() {
        $backTrace = array(
            $this->createBacktraceItem( '\DomDocument', __METHOD__ ),
            $this->createBacktraceItem( PhpUnitTestCaseInitialisationMatcher::CLASS_NAME, 'isInitialisationCall' ),
            $this->createBacktraceItem( self::CLASS_NAME, __METHOD__ ),
        );

        $this->assertFalse(
            $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall( $backTrace )
        );
    }
}
  
