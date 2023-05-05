<?php

abstract class Conn {

  static function get()
  {
    // start mongod process on your instance first
    $client = new MongoDB\Client('mongodb://localhost:27017');

    // select a database (will be created automatically if it not exists)
    return $db = $client->selectDatabase("library");
  }
}