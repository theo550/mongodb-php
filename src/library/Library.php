<?php

use MongoDB\BSON\ObjectId;

class Library {
  function index()
  {
    return Conn::get()->selectCollection('library')->find([], [
      'limit' => 10
    ])->toArray();
  }

  function create($book_version_id, $user_id, $note, $comment, $reading_state)
  {
    return Conn::get()->selectCollection('library')->insertOne([
      'book_version_id' => new ObjectId($book_version_id),
      'user_id' => new ObjectId($user_id),
      'note' => $note,
      'comment' => $comment,
      'reading_state' => $reading_state
    ]);
  }

  function find_books_in_library($user_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([
      ['$match' => ['user_id' => new ObjectId($user_id)]],
      ['$lookup' => [
        'from' => 'bookversion',
        'localField' => 'book_version_id',
        'foreignField' => '_id',
        'as' => 'result'
        ]],
      ['$lookup' => [
        'from' => 'book',
        'localField' => 'result.bookId',
        'foreignField' => '_id',
        'as' => 'result'
        ]
      ],
      ['$limit' => 10]
    ])->toArray();
  }

  function count_books_in_user_library($user_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([
      ['$match' => ['user_id' => new ObjectId($user_id)]],
      ['$count' => 'books_in_library']
    ])->toArray();
  }

  function count_readed_book_by_user($user_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$match' => [
        'user_id' => new ObjectId($user_id),
        'reading_state' => 'lu'
      ]],
      ['$count' => 'readed']
    ])->toArray();
  }

  function count_unread_book_by_user($user_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$match' => [
        'user_id' => new ObjectId($user_id),
        'reading_state' => 'non lu'
      ]],
      ['$count' => 'unread']
    ])->toArray();
  }

  function count_reading_book_by_user($user_id)
  {
    return Conn::get()->selectCollection('library')->aggregate([[
      '$match' => [
        'user_id' => new ObjectId($user_id),
        'reading_state' => 'en cours'
      ]],
      ['$count' => 'reading']
    ])->toArray();
  }

  function add_book_review($id, $note, $comment)
  {
    return Conn::get()->selectCollection('library')->updateOne(
      ['_id' => new ObjectId($id)],
      ['$set' => [
        'note' => (int)$note,
        'comment' => $comment
      ]]
    );
  }

  function count_books_by_author($author_name)
  {
    return Conn::get()->selectCollection('library')->aggregate([
      ['$lookup' => ['from' => 'bookversion', 'localField' => 'book_version_id', 'foreignField' => '_id', 'as' => 'book']],
      ['$lookup' => ['from' => 'book', 'localField' => 'book.bookId', 'foreignField' => '_id', 'as' => 'book']],
      ['$match' => ['book.authors' => $author_name]],
      ['$count' => 'book']
    ])->toArray();
  }
}
