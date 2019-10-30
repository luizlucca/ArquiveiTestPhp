<?php

namespace App\Http\Helper;

interface HttpClientInterface{
 public function doGet(String $url, $headers);
 public function doPost(String $url, Object $data);
}
