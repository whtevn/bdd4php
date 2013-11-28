<?php
	include 'testSuite/scenario.php';


	Fixture::at('fixtures');
	Scenario::when("testing some basic things", function($then){
		$then->beforeEach("set up the users", function($scene){
			$userFixture  = Fixture::get('users');
			$scene->frank = $userFixture->user_1;
			$scene->hulk  = $userFixture->user_2;
		})->
		the("first name of first user should be correct", function($users){
			expect($users->frank->first_name)->toBe("frank");
		})->
		the("first name of second user should be correct", function($users){
			expect($users->hulk->first_name)->toBe("hulk");
		})->
		the("last name of first user should not be wrong", function($users){
			expect($users->hulk->last_name)->not()->toBe("zappa");
		})->
		the("errors should fail loudly", function($users){
			barz;
			expect($users->hulk->last_name)->not()->toBe("hogan");
			expect($users->hulk->last_name)->toBe("zappa");
		});
	});

?>
