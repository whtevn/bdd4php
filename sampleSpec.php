<?php
	include 'testSuite/scenario.php';

	Scenario::when("#score")->
		beforeEach(function($scene){
			$users = Fixture::get('users');
			$scene->frank = $users->user_1;
			$scene->hulk = $users->user_2;
		})->
		it("first name of first user should be correct", function($scene){
			expect($scene->frank->first_name)->toBe("frank");
		})->
		it("first name of second user should be correct", function($scene){
			expect($scene->hulk->first_name)->toBe("hulk");
		})->
		it("last name of first user should not be wrong", function($scene){
			expect($scene->hulk->last_name)->not()->toBe("zappa");
		})->
		it("should show errors", function($scene){
			expect($scene->hulk->last_name)->not()->toBe("hogan");
			expect($scene->hulk->last_name)->toBe("zappa");
		});

?>
