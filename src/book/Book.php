<?php

use MongoDB\BSON\ObjectId;

class Book {

  function index()
  {
    return Conn::get()->selectCollection('book')->find([], [
      'limit' => 10
    ])->toArray();
  }

  function find_book_by_id($id)
  {
    return Conn::get()->selectCollection('book')->findOne([
      '_id' => new MongoDB\BSON\ObjectId($id)
    ], [
      'limit' => 10
    ]);
  }

  function get_book_by_title($title)
  {
    return Conn::get()->selectCollection('book')->find([
      'title' => $title
    ], [
      'limit' => 10
    ])->toArray();
  }

  function create($title, $description, $authors, $tags)
  {
    return Conn::get()->selectCollection('book')->insertOne([
      'title' => $title,
      'description' => $description,
      'authors' => [$authors],
      'tags' => [$tags]
    ]);
  }

  function delete($id)
  {
    Conn::get()->selectCollection('book')->deleteOne([
      '_id' => new MongoDB\BSON\ObjectId($id)
    ]);
  }

  function add_authors($book_id, $author_name)
  {
    return Conn::get()->selectCollection('book')->updateOne([
      '_id' => new ObjectId($book_id)
    ],
    [
      '$addToSet' => [
        'authors' => $author_name
      ]
    ]);
  }
  
  function add_tags($book_id, $tag)
  {
    return Conn::get()->selectCollection('book')->updateOne([
      '_id' => new ObjectId($book_id)
    ],
    [
      '$addToSet' => [
        'tags' => $tag
      ]
    ]);
  }

  function count_books_by_author($author_name)
  {
    return Conn::get()->selectCollection('book')->aggregate([
      ['$match' => ['authors' => ['$eq' => [$author_name]]]],
      ['$count' => 'nb_of_books']
    ])->toArray();
  }

  function get_book_comments($book_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$lookup' => [
        'from' => 'bookversion',
        'localField' => 'book_version_id',
        'foreignField' => '_id',
        'as' => 'bookversion'
      ]],
      ['$match' => [
        'bookversion.bookId' => new ObjectId($book_id)
      ]],
      ['$unset' => ['bookversion', 'reading_state', 'note', 'user_id', 'book_version_id']]
    ])->toArray();
  }

  function get_all_book_average()
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$group' => ['_id' => '$book_version_id', 'average' => ['$avg' => '$note']]
    ]])->toArray();
  }

  // here bookId is ObjectId of bookversion
  function get_book_average_note($book_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$match' => ['book_version_id' => new ObjectId($book_id)]],
      ['$group' => ['_id' => null, 'average' => ['$avg' => '$note']]]
    ])->toArray();
  }
}
