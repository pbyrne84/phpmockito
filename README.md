PHPMockito
==========

## Overview

This is a project which I wrote, this was for internal use at a company I worked for. I have upgraded it to run on php 7 and phpunit 7
which was fairly simple for a project last touched 9 years ago. Instead of using stringly typed([http://wiki.c2.com/?StringlyTyped](http://wiki.c2.com/?StringlyTyped)) 
calls such as

```java
mockLoader.expect(once())
    .method("load").with( eq(KEY) )
    .will( returnValue(VALUE) );
```

which are not automated refactor safe it used the standard mockito call format e.g.

```php
$mock = mock(className::class);
when($mock->load(KEY))->thenReturn(VALUE)
when($mock->load(KEY))->thenThrow(new Exception())
```

Also using strings for methods names and var args/arrays for parameters is really hard to code and debug as all the
auto complete and inspections are broken. I believe a lot of ideology against mocking is how unfriendly it can be
depending on implementation. Ideology is tied to effort and ability which is in turn tied to implementation, implementation
is tied to the programming environment. All languages are not equal due to this, personal will and enthusiasm masks over the cracks.

I like generics, some implementations are hard to read, but it is just formalising what we tend to do anyway in more
loosey goosey languages, this gives us decent get tool support and lowers human memorisation requirements.

The irony is when approaches allow more breathing space they can get taken to a new point of psychological destruction, like replacing 
xml with yaml, we can get to a point of manually edited yaml files are 4000 lines long, that is a point of tetering sanity, after that
all yaml whatever size can have a bad taste. Like food poisoning can put you off a type of food.

The generic call resolution was originally managed by the DynamicReturnTypePlugin for IDEA/PHPStorm

[https://github.com/pbyrne84/DynamicReturnTypePlugin](https://github.com/pbyrne84/DynamicReturnTypePlugin)

But with modern advanced versions of PHPStorm we can use the template doc

```php
/**
 * @template T
 *
 * @param T   $mockedClass
 * @param int $expectedCallCount
 *
 * @return T
 */
```

This seems to handle ::class and object instances. Strings of classnames does not seem to work and relies on 
using phpstorm metadata
[https://github.com/pbyrne84/phpstorm-metadata-example](https://github.com/pbyrne84/phpstorm-metadata-example)

An example can be found here of a full end to end suite can be found here

[https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/EndToEnd/MockitoBasicEndToEndTest.php](https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/EndToEnd/MockitoBasicEndToEndTest.php)

The when call returns a reference which can be used in the **verifyMethodCall** call.

```php
public function test_mock_magic_call_returnValue() {
    $usageTestClass       = new UsageTestClass( mock( '\DomDocument' ) );
    $magicMethodTestClass = mock( MagicMethodTestClass::class );

    $magicMethodCallResult   = 'magicMethodCall result';
    $fullyActionedMethodCall =
            when( $magicMethodTestClass->__call( 'magicMethodCall', array( 'testValue' ) ) )
                    ->thenReturn( $magicMethodCallResult );

    $this->assertEquals( $magicMethodCallResult, $usageTestClass->testMagicMethods( $magicMethodTestClass ) );

    verifyMethodCall( $fullyActionedMethodCall );
    verify( $magicMethodTestClass )->__call( 'magicMethodCall', array( 'testValue' ) );
    verifyNoMoreInteractions( $magicMethodTestClass );
}
```

## Design

### Mocks

The mocks are done using inheritance and eval, a list of all the public methods signatures are collected and then override
the original class blocking off all direct access but keeping the signature. This is something that would need to be revisited
if upgrading to newer versions of php as there is lots of fun new stuff to take into account.

#### Calls
When a call is made it analyses via the stacktrace to see if we are in a test case.

[https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/Expectancy/PhpUnitTestCaseInitialisationMatcher.php](https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/Expectancy/PhpUnitTestCaseInitialisationMatcher.php)

If we are in a test case we don't log it as a production call which would interfere with the verify count.

The [PHPMockito\Run\RunTimeState](https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/Run/RuntimeState.php)
class handles all the registrations of expectancies as it has a static instance of InitialisationCallRegistrarImpl.
This allows us to easily do **verifyNoMoreInteractions** and not have overly complicated mocking code as 
**eval** can be **evil**.


#### Mock code generation
The code generator can be found here [https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/Mock/MockClassCodeGenerator.php](https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/Mock/MockClassCodeGenerator.php).


##### Example mock code generation

```php
class A {
    private function p() {

    }


    protected function q() {

    }


    public function a( array $param1, \DOMDocument $param2 ) {

    }


    public function b( array $param1, \PHPUnit\Framework\AssertionFailedError $param2 ) {

    }

}
```

generates the following mock code

```php
namespace PHPMockito\Action{
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class A_PhpMockitoMock extends A  implements  MockedClass {
        private $mockedClassConstructorParams;
        private $defaultValueMap = array(
            'a' => array(),
            'b' => array(),

        );

        function __construct( MockedClassConstructorParams $mockedClassConstructorParams ){
            $this->mockedClassConstructorParams = $mockedClassConstructorParams;
        }


        public function getInstanceReference(){
            return $this->mockedClassConstructorParams->getInstanceReference();
        }


        public function getMethodsDefaultParameterMap( $methodName ){
            if( !$this->hasMockedMethod( $methodName ) ){
                throw new \InvalidArgumentException( 'Default values not set for method ' . $methodName );
            }

            return $this->defaultValueMap[ $methodName ];
        }


        public function hasMockedMethod( $methodName ){
            return array_key_exists( $methodName, $this->defaultValueMap );
        }


        public function a( array $param1, \DOMDocument $param2 ) {
            $methodCall = new DebugBackTraceMethodCall(
                $this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                $this,
                'a',
                count(array($param1, $param2)) < count( func_get_args() ) ? func_get_args() :  array($param1, $param2),
                debug_backtrace()
            );

            $this->mockedClassConstructorParams->registerCall( $methodCall );
            $actionedCall = $this->mockedClassConstructorParams->actionCall( $methodCall );

            return $actionedCall;
        }

        public function b( array $param1, \PHPUnit\Framework\AssertionFailedError $param2 ) {
            $methodCall = new DebugBackTraceMethodCall(
                $this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                $this,
                'b',
                count(array($param1, $param2)) < count( func_get_args() ) ? func_get_args() :  array($param1, $param2),
                debug_backtrace()
            );

            $this->mockedClassConstructorParams->registerCall( $methodCall );
            $actionedCall = $this->mockedClassConstructorParams->actionCall( $methodCall );

            return $actionedCall;
        }
    }
```

There is no call to the parent constructor which means we do not get failures from parent initialisation.

Singletons are generally bad but in this case we are working around a highly opinionated runtime environment,
it is there for user-friendliness and reduce user dancing when writing a test. Maybe a trait import but all this 
stuff was originally written for php 5.3.

## Expectancy evaluation and errors

This is where it gets fun. PHP doesn't really have an equals/toString mechanism. It is not just that we want
to confirm a match but show a failed match in a human form. We often do not want to compare on instance but
the value of an instance. For example, we care about the xml that a DomDocument has in it with when generated and
passed to something else, we also probably do not care about the formatting of the xml and compare against to 
equally normalised instances. Whitespace issues are no-ones idea of fun in a failing test.

This is all handled by instances of the [https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/ToString/ToStringAdaptor.php](https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/ToString/ToStringAdaptor.php)
which are created by type from the [https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/ToString/ToStringAdaptorFactory.php](https://github.com/pbyrne84/phpmockito/blob/master/lib/PHPMockito/ToString/ToStringAdaptorFactory.php)

The mappings for object types are held in 

```php
function __construct() {
    $this->typeMappings = array(
            MockedClass::class => MockedClassToStringAdaptor::class,
            '\DomDocument'     => DomDocumentToStringAdaptor::class,
            '\SplFileInfo'     => SplFileInfoToStringAdaptor::class,
            '\Exception'       => ExceptionToStringAdaptor::class,
    );
}
```

If no custom object matcher can be found it will try converting to an array which can be fatal.

### The code for the PHPMockito\ToString\DomDocumentToStringAdaptor
```php
namespace PHPMockito\ToString;


class DomDocumentToStringAdaptor extends ToStringAdaptor {
    
    /** @var \DOMDocument */
    private $domDocument;


    /**
     * @param \DOMDocument $document
     */
    function __construct( \DOMDocument $document ) {
        $this->domDocument = $document;
    }


    function toString( $indentation = 0 ) {
        $xml                              = $this->domDocument->saveXML();
        $oDomDocument                     = new \DOMDocument( '1.0' );
        $oDomDocument->preserveWhiteSpace = false;
        $oDomDocument->formatOutput       = true;
        $oDomDocument->loadXML( $xml );

        $xml = $oDomDocument->saveXML();

        return get_class( $this->domDocument ) . "(" . strlen( $xml ) . ") '" . $xml . "'";
    }
}
```


## Verification

An example of the generated messages when changing [https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/EndToEnd/MockitoBasicEndToEndTest.php](https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/EndToEnd/MockitoBasicEndToEndTest.php)
to an expected call count of 4 from 2

```php
verify( $DOMDocument, 4 )->cloneNode( true );
```

in

```php
    public function test_mock_returnValue() {
        $DOMDocument = mock( '\DomDocument' );

        $methodCall1 =
                when( $DOMDocument->cloneNode( true ) )
                        ->thenReturn( 'MOO' )
                        ->thenReturn( 'FOO' )
                        ->thenReturn( 'GOO' );

        $methodCall2 =
                when( $DOMDocument->cloneNode( null ) )
                        ->thenReturn( 'Baaa' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue(), 'a' );
        $this->assertEquals( 'FOO', $usageTestClass->testTrue(), 'b' );
        $this->assertEquals( 'Baaa', $usageTestClass->testDefault() );
        $this->assertEquals( 'Baaa', $usageTestClass->testManualDefault() );

        $this->assertEquals(
                'GOO',
                $DOMDocument->cloneNode( true ),
                'Returns the mocked value when called in the test'
        );

        verify( $DOMDocument, 4 )->cloneNode( true );****
        verify( $DOMDocument, 2 )->cloneNode();
        verify( $DOMDocument, 2 )->cloneNode( null );

        verifyMethodCall( $methodCall1, 2 );
        verifyMethodCall( $methodCall2, 2 );
    }

```

returns a nice pretty printed error
```txt
*** Call expected 4 time/s: ***
DOMDocument_PhpMockitoMock->cloneNode(
  $deep(arg[0]) = boolean(1) true
)
*** Actual calls :- ***
DOMDocument_PhpMockitoMock->cloneNode(
  $deep(arg[0]) = boolean(1) true
)
DOMDocument_PhpMockitoMock->cloneNode(
  $deep(arg[0]) = boolean(1) true
)
DOMDocument_PhpMockitoMock->cloneNode(
  $deep(arg[0]) = NULL(0) NULL
)
DOMDocument_PhpMockitoMock->cloneNode(
  $deep(arg[0]) = NULL(0) NULL
)
```

There is the limitation that internal functions cannot return the default values via reflection so the defaults match to null 
hence 

```php
verify( $DOMDocument, 2 )->cloneNode();
verify( $DOMDocument, 2 )->cloneNode( null );

```

works as it is not 
```php
verify( $DOMDocument, 2 )->cloneNode( false );
verify( $DOMDocument, 2 )->cloneNode( null );
```

Which is the documented default. Limits of the reflection engine.

### End to end test

All the ToStringAdaptors are tested by the factory test here.
[https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/ToString/ToStringAdaptorFactoryTest.php](https://github.com/pbyrne84/phpmockito/blob/master/test/PHPMockito/ToString/ToStringAdaptorFactoryTest.php)

## Issues in upgrading to PHP 7 from 5.3
There as some issue using the latest xdebug(php_xdebug-2.9.5-7.4-vc15-nts-x86_64.dll) for php 7 as on windows it was throwing 
-1073741819 errors when  trying to throw an exception from an expectancy set by 

```php
$DOMDocument = mock( '\DomDocument');
when( $DOMDocument->cloneNode( true ) )
        ->thenThrow( new TestException("xx") );
```

This is potentially something to do with throwing exceptions in eval code.

