<?php

include "lib/ctm.php";

// upgrade to an auth token, you can replace $_ENV['CTM_API_KEY'] with your access_key and $_ENV['CTM_API_SECRET'] with your secret_key
// an auth token is used to authenticate you to different API's
$auth_token = AuthToken::authorize($_ENV["CTM_API_KEY"],$_ENV["CTM_API_SECRET"]);
if ($auth_token) {
  echo "auth_token: {$auth_token->token}\n";
} else {
  echo "invalid credentials\n";
}
// the default account_id for your auth token will be the first account in your agency.
// you change the account_id by setting it on the auth_token.

// get an existing number by ID
$number = Number::get($auth_token, 42);

// change the numbers name to test
$number->name = "test";
$number->save();
// remove the numbers name
$number->name = "";
$number->save();

// look for numbers within the 410 area code
$numbers_to_buy = Number::search($auth_token, "410");
var_dump($numbers_to_buy[0]);

// buy the first number in the list
$number = Number::buy($auth_token, $numbers_to_buy[0]->number);
var_dump($number);

// add a receiving number
$rnid = $number->addReceivingNumber("+18889994444");
var_dump($rnid);

// configure a tracking source so any visitor who originates from google.com and lands on a page with gclid=.+ will see this tracking number
$number->setSource("test", "google.com", "gclid=.+", 88);

// we could remove the receiving number like this
//$number->removeReceivingNumber($rnid);

// now we can release the number
$number->release();

// get a list of all the numbers within our account
$numbers = Number::list_numbers($auth_token);
var_dump($numbers);

// switch into a different account context
$auth_token->account_id = 25;

// list the numbers in the other account
$numbers = Number::list_numbers($auth_token);
var_dump($numbers);

?>
