<?php

class Users {

  function index()
  {
    return Conn::get()->selectCollection('users')->find([], [
      "limit" => 10
    ])->toArray();
  }

  function get_user_by_id($id)
  {
    return Conn::get()->selectCollection('users')->findOne([
      '_id' => new MongoDB\BSON\ObjectId($id)
    ]);
  }

  function find_user_by_name($user_name)
  {
    return Conn::get()->selectCollection('users')->findOne([
      'name' => $user_name,
    ]);
  }

  function create($name)
  {
    return Conn::get()->selectCollection('users')->insertOne([
      'name' => $name
    ]);
  }

  function update_user($id, $name)
  {
    return Conn::get()->selectCollection('users')->updateOne([
      '_id' => new MongoDB\BSON\ObjectId($id)
    ],
    [
      '$set' => ['name' => $name]
    ]);
  }

  function delete($id)
  {
    return Conn::get()->selectCollection('users')->deleteOne([
      '_id' => new MongoDB\BSON\ObjectId($id)
    ]);
  }
}
