Call Tracking Metrics PHP Library
=================================

Authentication
--------------

Authentication to CTM Service works by sending a request to retrieve an access token.  Then each request will include the access token.

The access token is short lived and grants you access to specific set of accounts generally if you have a multi access account and use 
agency level API keys it will give you access to all the account's within your agency.  If you have a single access account the API keys will
only grant you access to that one account.

All API requests use an auth token, that has an associated account id.   To change the target account, change the id on the auth token.


Example Usage
-------------
```php

// include the library
include "lib/ctm.php";

// authenticate to get an access token
$auth_token = AuthToken::authorize($_ENV["CTM_API_KEY"],$_ENV["CTM_API_SECRET"]);
if ($auth_token) {
  echo "auth_token: {$auth_token->token}\n";
} else {
  echo "invalid credentials\n";
}

// get an existing number by ID given a valid auth token
$number = Number::get($auth_token, 42);

// update the number's name
$number->name = "test";
$number->save();

// find new numbers to purchase
$numbers_to_buy = Number::search($auth_token, "410");
var_dump($numbers_to_buy[0]);

// purchase the first number in the result list
$number = Number::buy($auth_token, $numbers_to_buy[0]->number);
var_dump($number);

// assign a receiving number to the newly purchased number
$rnid = $number->addReceivingNumber("+18889994444");
var_dump($rnid);

// assign a tracking source - e.g. a name and set of conditions that define when a website visitor should see this number
$number->setSource("test", "google.com", "gclid=.+", 88);

// remove a receiving number from this number
//$number->removeReceivingNumber($rnid);

// release the number
$number->release();

// get a paginated list of numbers available to the current account
$numbers = Number::list_numbers($auth_token);
var_dump($numbers);

// change account based on access
$auth_token->account_id=xxxx // where xxxx is another account id within your available accounts

```


