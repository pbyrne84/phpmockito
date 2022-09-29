<?php
namespace PHPMockito\Expectancy;
use PHPUnit\Framework\TestCase;

class PhpUnitTestCaseInitialisationMatcherTest extends TestCase {

    
    /**
     * @var PhpUnitTestCaseInitialisationMatcher
     */
    private $phpUnitTestCaseInitialisationMatcher;



    protected function setUp() : void {
        $this->phpUnitTestCaseInitialisationMatcher = new PhpUnitTestCaseInitialisationMatcher();
    }


    public function test_checkIsInitialisationCall_throwsExceptionOnEmptyBackTrace() {
        $this->expectException(\InvalidArgumentException::class);
        $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall( array() );
    }

    
    public function test_checkIsInitialisationCall_throwsExceptionIfClassIsNotFound() {
        $this->expectException(\InvalidArgumentException::class);
        $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall(
            array( array( 'function' => 'functionName' ) )
        );
    }


    public function test_checkIsInitialisationCall_matchesAsADescendantOfPhpUnitIsSecond() {
        $backTrace = array(
            $this->createBacktraceItem( '\DomDocument', __METHOD__ ),
            $this->createBacktraceItem( self::class, __METHOD__ ),
            $this->createBacktraceItem( PhpUnitTestCaseInitialisationMatcher::class, 'isInitialisationCall' ),
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
            $this->createBacktraceItem( PhpUnitTestCaseInitialisationMatcher::class, 'isInitialisationCall' ),
            $this->createBacktraceItem( self::class, __METHOD__ ),
        );

        $this->assertFalse(
            $this->phpUnitTestCaseInitialisationMatcher->checkIsInitialisationCall( $backTrace )
        );
    }
}
  
