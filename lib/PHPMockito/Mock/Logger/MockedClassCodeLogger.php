<?php
namespace PHPMockito\Mock\Logger;

interface  MockedClassCodeLogger {

    /**
     * @param string $mockFullyQualifiedName
     * @param string $code
     *
     * @return mixed
     */
    public function logMockCode( $mockFullyQualifiedName, $code );

}
 