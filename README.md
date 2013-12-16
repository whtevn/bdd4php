# bdd4php

simple bdd proof of concept for php

## Basic Syntax

Looks like this. Look at sampleSpec.php file included in the project for
a more detailed example.

	Scenario::when("name the scenario here", function($then){
		$then->beforeEach("set up the initial conditions", function($scene){
			$scene->foo  = "bar"
		})->
		the("first name of first user should be correct", function($scene){
			expect($scene->foo)->toBe("bar");
		})->
		the("first name of first user should be correct", function($scene){
			expect($scene->foo)->toBe("bar");
		})->

## sampleSpec.php

While sampleSpec.php is not something you will use while developing,
it is a helpful starting guide. 

To see it at work, after cloning the project.

	$ cd bdd4php/
	$ php sampleSpec.php

There are 3 passing tests and 2 failing tests by design

## Syntax explained

**Scenario**: The scenario class kicks off a series of tests. All test segments
are preceded by a call to Scenario::when($title, $scenarioFunction)

	  Scenario::when("name the scenario here", function($then){
	    ...
		})

**beforeEach**: sets up the conditions for the tests that will follow within
this scenario. Called as a instance method on the scenario instance passed in
through the Scenario::when call. beforeEach is run before each call to 'the()'

		$then->beforeEach("set up the initial conditions", function($scene){
			...
		})

    // or...

		$then->beforeEach(function($scene){
			...
		})

beforeEach() can be called a second time, and the function given will be run
after the previously given beforeEach(). To stop chaining beforeEach() calls,
end the Scenario.

**afterEach**: functions that run after each the() block. Mirros beforeEach in 
every way. 

    $then->afterEach("tear down the initial conditions", function($scene){
			...
		})

**the**: sets up further conditions for particular tests, and provides context for
the expect() command. 

		the("first name of first user should be correct", function($scene){
			...
		})->

**expect**: Expects are called from inside the() blocks, although I suspect that expect()
can be called from anywhere within the Scenario::when() function, but I have not tried it.

    expect("bar")->toBe("bar");
    expect("bar")->not()->toBe("baz");
    expect("bar")->toBeTypeOf("String");
    expect("bar")->not()->toBeTypeOf("String");

Once the Scenario::when() context comes into play, everything except expect() returns 
the same instance of Scenario that is passed in through when(). This means that the() can
be called from beforeEach() or another the(), and the same is true for beforeEach().

## Suggested Project Layout

Assuming my project looks something like this:

    myProject/
		  itemSet.php
			
Add a specification directory with a place for mocks, stubs, and fixtures. Then clone bdd4php
into it so that your project now looks like this:

    myProject/
     itemSet.php
     spec/
       mocks/
       stubs/
       fixtures/
       bdd4php/
         testSuite/
				   
There are other directories under testSuite/, but for the purposes of
using the project (and not developing it), they can safely be ignored.

