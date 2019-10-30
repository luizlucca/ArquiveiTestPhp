<?php

namespace App\Http\Consumers;

use App\NfeNote;

interface NfeConsumerInterface{
    public function consumeNfe(String $message) : NfeNote;
}
