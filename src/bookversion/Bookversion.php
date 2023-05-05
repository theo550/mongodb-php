<?php

use MongoDB\BSON\ObjectId;

class Bookversion {

  function index()
  {
    return Conn::get()->selectCollection('bookversion')->aggregate([
      ['$sort' => ['created_at' => -1]],
      ['$limit' => 10]
    ])->toArray();
  }

  function create($book_id, $edition)
  {
    return Conn::get()->selectCollection('bookversion')->insertOne([
      'bookId' => new ObjectId($book_id),
      'edition' => $edition,
      'created_at' => date("Y-m-d")
    ]);
  }

  function find_bookverion_by_id()
  {
    // return Conn::get()->selectCollection('bookversion')->aggregate([[
    //   '$lookup' => [
    //     'from' => 'bookversion',
    //     'localField' => 'book_version_id',
    //     'foreignField' => '_id',
    //     'as' => 'book'
    //     ]],
    //     [
    //       '$lookup' => [
    //         'from' => 'book',
    //         'localField' => 'book.bookId',
    //         'foreignField' => '_id',
    //         'as' => 'book'
    // ]]]);
  }
}
