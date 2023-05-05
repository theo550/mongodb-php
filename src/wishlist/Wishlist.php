<?php

use MongoDB\BSON\ObjectId;

class Wishlist {
  function index()
  {
    return Conn::get()->selectCollection('wishlist')->find([], [
      'limit' => 10
    ])->toArray();
  }

  function create($book_version_id, $user_id)
  {
    return Conn::get()->selectCollection('wishlist')->insertOne([
      'book_version_id' => new ObjectId($book_version_id),
      'user_id' => new ObjectId($user_id),
    ]);
  }

  function find_user_wishlist($user_id)
  {
    return Conn::get()->selectCollection('wishlist')->aggregate([
      ['$match' => ['user_id' => new ObjectId($user_id)]],
      ['$lookup' => [
        'from' => 'bookversion',
        'localField' => 'book_version_id',
        'foreignField' => '_id',
        'as' => 'bookversion'
      ]],
      ['$lookup' => [
        'from' => 'book',
        'localField' => 'bookversion.bookId',
        'foreignField' => '_id',
        'as' => 'book'
      ]],
      ['$limit' => 10]
    ])->toArray();
  }

  function delete_from_user_wishlist($wishlist_id, $user_id)
  {
    return Conn::get()->selectCollection('wishlist')->deleteOne([
      '_id' => new ObjectId($wishlist_id),
      'user_id' => new ObjectId($user_id)
    ]);
  }

  function count_books_in_user_wishlist($user_id)
  {
    return Conn::get()->selectCollection('wishlist')->aggregate([
      ['$match' => ['user_id' => new ObjectId($user_id)]],
      ['$count' => 'books_in_wishlist']
    ])->toArray();
  }

  function count_books_by_author($author_name)
  {
    return Conn::get()->selectCollection('wishlist')->aggregate([
      ['$lookup' => ['from' => 'bookversion', 'localField' => 'book_version_id', 'foreignField' => '_id', 'as' => 'book']],
      ['$lookup' => ['from' => 'book', 'localField' => 'book.bookId', 'foreignField' => '_id', 'as' => 'book']],
      ['$match' => ['book.authors' => $author_name]],
      ['$count' => 'book']
    ])->toArray();
  }
}
