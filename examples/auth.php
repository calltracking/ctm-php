<?php

include "lib/ctm.php";

$auth_token = AuthToken::authorize($_ENV["CTM_API_KEY"],$_ENV["CTM_API_SECRET"]);
if ($auth_token) {
  echo "auth_token: {$auth_token->token}\n";
} else {
  echo "invalid credentials\n";
}

?>
