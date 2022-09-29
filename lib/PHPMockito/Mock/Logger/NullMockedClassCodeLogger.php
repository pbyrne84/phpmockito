<?php

namespace PHPMockito\Mock\Logger;


class NullMockedClassCodeLogger implements MockedClassCodeLogger {
    

    /**
     * @inheritdoc
     */
    public function logMockCode( $mockFullyQualifiedName, $code ) {
    }
}
 