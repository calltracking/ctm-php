<?php

class Number {
  private function __construct($id, $number, $auth_token, $name="") {
    $this->id = $id;
    $this->number = $number;
    $this->auth_token = $auth_token;
    $this->name = $name;
  }

  public static function get($auth_token, $id) {
    $url = Config::endpoint() . "/accounts/{$auth_token->account_id}/numbers/{$id}.json?auth_token=" . $auth_token->token;
    $res = Requests::get($url);
    $data = json_decode($res->body,true);
    var_dump($res->body);
    if ($data['id']) {
      echo "number: {$data['number']}\n";
      return new Number($data['id'], $data['number'], $auth_token, $data['name']);
    } else {
      return null;
    }
  }

  public function save() {
    $url = Config::endpoint() . "/accounts/{$this->auth_token->account_id}/numbers/{$this->id}.json?auth_token=" . $this->auth_token->token;
    $res = Requests::put($url, array(), array("name" => $this->name));
    return true;
  }

  public static function search($auth_token, $areacode, $country_code="US") {
    $url = Config::endpoint() . "/accounts/{$auth_token->account_id}/numbers/search.json?auth_token=" . $auth_token->token .
                                "&area_code=" . $areacode . "&country_code=" . $country_code;
    $res = Requests::get($url);
    $data = json_decode($res->body);
    var_dump($data);
    $numbers = array();
    foreach ($data->results as $result) {
      array_push($numbers, new Number(-1, $result->phone_number, $auth_token));
    }
    return $numbers;
  }
  public static function buy($auth_token, $number_digits) {
    $url = Config::endpoint() . "/accounts/{$auth_token->account_id}/numbers.json?auth_token=" . $auth_token->token;
    $res = Requests::post($url, array(), array("phone_number" => $number_digits));
    $data = json_decode($res->body);
    if ($data->status == "success") {
      return new Number($data->number->id, $data->number->number, $auth_token);
    } else {
      return null;
    }
  }

  public function release() {
    $url = Config::endpoint() . "/accounts/{$this->auth_token->account_id}/numbers/{$this->id}.json?auth_token=" . $this->auth_token->token;
    $res = Requests::delete($url);
    $data = json_decode($res->body);
    return $data->released;
  }

  public static function list_numbers($auth_token, $page=0) {
    $page += 1;
    $url = Config::endpoint() . "/accounts/{$auth_token->account_id}/numbers.json?auth_token=" . $auth_token->token . "&page=$page";
    $res = Requests::get($url);
    $data = json_decode($res->body);
    $numbers = array();
    foreach ($data->numbers as $number) {
      array_push($numbers, new Number($number->id, $number->number, $auth_token, $number->name));
    }
    return new Page($numbers, $page, $data->total_entries, $data->total_pages);
  }

  // return the receiving number id
  public function addReceivingNumber($number) {
    $url = Config::endpoint() . "/accounts/{$this->auth_token->account_id}/numbers/{$this->id}/receiving_numbers.json?auth_token=" . $this->auth_token->token;
    $res = Requests::post($url, array(), array("number" => $number));
    $data = json_decode($res->body);
    if ($data->status == "success") {
      return $data->receiving_number->id;
    } else {
      return null;
    }
  }

  // remove the receiving number by id
  public function removeReceivingNumber($number_id) {
    $url = Config::endpoint() . "/accounts/{$this->auth_token->account_id}/numbers/{$this->id}/receiving_numbers/{$number_id}.json?auth_token=" . $this->auth_token->token;
    $res = Requests::delete($url);
    $data = json_decode($res->body);
    return ($data->status == "success");
  }

  public function setSource($name, $referrer, $location, $position=10) {
    $url = Config::endpoint() . "/accounts/{$this->auth_token->account_id}/numbers/{$this->id}/tracking_sources.json?auth_token=" . $this->auth_token->token;
    $res = Requests::post($url, array(), array("name" => $name, "ref_pattern" => $referrer, "url_pattern" => $location, "position" => $position));
    $data = json_decode($res->body);
    return ($data->status == "success");
  }

}

?>
