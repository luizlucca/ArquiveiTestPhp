<?php


namespace App\Http\Publisher;


interface PublisherInterface
{
    public function publish(String $message);
}
