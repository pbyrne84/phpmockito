<?php

namespace PHPMockito\Mock\Logger;


class FileBasedMockedClassCodeLogger implements MockedClassCodeLogger {
    const CLASS_NAME = __CLASS__;


    public function logMockCode( $code ) {
        file_put_contents( __DIR__ . '/output/mockCode.php', "<?php\n" . $code );
    }
}
 