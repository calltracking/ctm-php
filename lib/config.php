<?php

class Config {
  public static function endpoint() {
    $endpoint = $_ENV["CTM_TEST_ENDPOINT"];
    if (isset($endpoint)) {
      return $endpoint;
    } else {
		  return "https://api.calltrackingmetrics.com/api/v1";
    }
  }
}

?>
