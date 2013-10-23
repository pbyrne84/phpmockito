<?php

namespace PHPMockito\Mock\Logger;


class NullMockedClassCodeLogger implements MockedClassCodeLogger {
    const CLASS_NAME = __CLASS__;


    /**
     * @inheritdoc
     */
    public function logMockCode( $mockFullyQualifiedName, $code ) {
    }
}
 