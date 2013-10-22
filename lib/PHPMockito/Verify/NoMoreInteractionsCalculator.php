<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\MethodCall;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Signature\SignatureGenerator;

class NoMoreInteractionsCalculator {
    const CLASS_NAME = __CLASS__;

    /** @var SignatureGenerator */
    private $signatureGenerator;


    /**
     * @param SignatureGenerator $signatureGenerator
     */
    function __construct( SignatureGenerator $signatureGenerator ) {
        $this->signatureGenerator = $signatureGenerator;
    }


    /**
     * @param MethodCall[]                 $actualInteractions
     * @param MethodCall[]                 $verifiedInteractions
     * @param \PHPMockito\Mock\MockedClass $mockedClass
     *
     * @return string
     */
    public function calculateNonVerifiedInteractions( array $actualInteractions,
                                                      array $verifiedInteractions,
                                                      MockedClass $mockedClass ) {
        $classInstanceReference     = $mockedClass->getInstanceReference();
        $filteredActualInteractions = $this->filterInteractionOnInstanceName(
            $actualInteractions,
            $classInstanceReference
        );

        $filteredVerifiedInteractions = $this->filterInteractionOnInstanceName(
            $verifiedInteractions,
            $classInstanceReference
        );

        $callCounter             = 0;
        $nonVerifiedInteractions = '';
        foreach ( $filteredActualInteractions as $message => $interaction ) {
            if ( !array_key_exists( $message, $filteredVerifiedInteractions ) ) {
                $nonVerifiedInteractions .= 'Call ' . ++$callCounter . ":\n" .
                        $message . "\n\n";
            }
        }


        if ( $nonVerifiedInteractions !== '' ) {
            $nonVerifiedInteractions = $callCounter . ' Method calls NOT verified for ' .
                    get_class( $mockedClass ) . ' (' . $classInstanceReference . ') :' . "\n\n"
                    . $nonVerifiedInteractions;
        }

        return $nonVerifiedInteractions;
    }


    /**
     * @param MethodCall[] $interactions
     * @param string       $classInstanceReference
     *
     * @return MethodCall[]
     */
    private function filterInteractionOnInstanceName( array $interactions, $classInstanceReference ) {
        $filteredInteractions = array();
        foreach ( $interactions as $interaction ) {
            $instanceReference = $interaction->getClass()
                    ->getInstanceReference();
            if ( $instanceReference == $classInstanceReference ) {
                $filteredInteractions[ $this->signatureGenerator->generateMessage( $interaction ) ] = $interaction;
            }
        }

        return $filteredInteractions;
    }

}
 