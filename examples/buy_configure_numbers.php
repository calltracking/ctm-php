<?php

include "lib/ctm.php";

$auth_token = AuthToken::authorize($_ENV["CTM_API_KEY"],$_ENV["CTM_API_SECRET"]);
if ($auth_token) {
  echo "auth_token: {$auth_token->token}\n";
} else {
  echo "invalid credentials\n";
}

$number = Number::get($auth_token, 42);

$number->name = "test";
$number->save();
$number->name = "";
$number->save();

$numbers_to_buy = Number::search($auth_token, "410");
var_dump($numbers_to_buy[0]);
$number = Number::buy($auth_token, $numbers_to_buy[0]->number);
var_dump($number);

$rnid = $number->addReceivingNumber("+18889994444");
var_dump($rnid);
$number->setSource("test", "google.com", "gclid=.+", 88);

//$number->removeReceivingNumber($rnid);

$number->release();

$numbers = Number::list_numbers($auth_token);
var_dump($numbers);

?>
