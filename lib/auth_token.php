<?php

class AuthToken {
  private function __construct($token, $current_account_id) {
    $this->token = $token;
    $this->account_id = $current_account_id;
  }
  public static function authorize($api_key, $api_secret) {
    $url = Config::endpoint() . "/authentication.json";
    #echo "send request to $url\n";
    $res = Requests::post($url, array(), array("token" => $api_key, "secret" => $api_secret));
    $data = json_decode($res->body);
    if ($data->success) {
      return new AuthToken($data->token, $data->first_account->id);
    } else {
      return null;
    }
  }
}

?>
