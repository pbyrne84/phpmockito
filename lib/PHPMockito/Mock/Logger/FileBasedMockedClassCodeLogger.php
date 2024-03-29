<?php

namespace PHPMockito\Mock\Logger;


class FileBasedMockedClassCodeLogger implements MockedClassCodeLogger {
    

    /**
     * @inheritdoc
     */
    public function logMockCode( $mockFullyQualifiedName, $code ) {
        file_put_contents( __DIR__ . '/output/' . str_replace('\\', '_', $mockFullyQualifiedName). '.php', "<?php\n" . $code);
    }
}
 