<?php
require_once './src/users/Users.php';
require_once './src/book/Book.php';
require_once './src/bookversion/Bookversion.php';
require_once './src/library/Library.php';
require_once './src/wishlist/Wishlist.php';

if (isset($_GET['users'])) {
  $user = new Users;
  if(isset($_GET['name']) && !isset($_GET['update'])) {
    echo json_encode($user->find_user_by_name($_GET['name']));
  } else if (isset($_GET['user_id']) && !isset($_GET['delete']) && !isset($_GET['update'])) {
    echo json_encode($user->get_user_by_id($_GET['user_id']));
  } else if (isset($_GET['create_name'])) {
    echo json_encode($user->create($_GET['create_name'])); 
  } else if (isset($_GET['delete'])) {
    $user->delete($_GET['user_id']);
  } else if (isset($_GET['update'])) {
    $user->update_user($_GET['user_id'], $_GET['name']);
  } else {
    echo json_encode($user->index());
  }
}

if (isset($_GET['books'])) {
  $book = new Book;
  if(isset($_GET['new'])) {
    $book->create($_GET['title'], $_GET['description'], $_GET['authors'], $_GET['tags']);
  } else if (isset($_GET['author'])) {
    echo json_encode($book->add_authors($_GET['bookId'], $_GET['author']));
  } else if (isset($_GET['addTag'])) {
    echo json_encode($book->add_tags($_GET['bookId'], $_GET['addTag']));
  } else if (isset($_GET['comments'])) {
    echo json_encode($book->get_book_comments($_GET['comments']));
  } else if (isset($_GET['note'])) {
    echo json_encode($book->get_book_average_note($_GET['bookId']));
  } else if (isset($_GET['average'])) {
    echo json_encode($book->get_all_book_average());
  } else if (isset($_GET['book_id'])) {
    echo json_encode($book->find_book_by_id($_GET['book_id']));
  } else if (isset($_GET['delete'])) {
    echo json_encode($book->delete($_GET['id']));
  } else if (isset($_GET['title'])) {
    echo json_encode($book->get_book_by_title($_GET['title']));
  } else if (isset($_GET['authorName'])) {
    echo json_encode($book->count_books_by_author($_GET['authorName']));
  } else {
    echo json_encode($book->index());
  }
}

if (isset($_GET['book_version'])) {
  $bookversion = new Bookversion;

  if (isset($_GET['create'])) {
    $bookversion->create($_GET['id'], $_GET['edition']);
  } else {
    echo json_encode($bookversion->index());
  }
}

if (isset($_GET['library'])) {
  $library = new Library;

  if (isset($_GET['create'])) {
    $library->create($_GET['bookversion'], $_GET['userId'], $_GET['note'], $_GET['comment'], $_GET['reading_state']);
  } else if (isset($_GET['stat'])) {
    echo json_encode($library->count_books_in_user_library($_GET['userId']));
  } else if (isset($_GET['author'])) {
    echo json_encode($library->count_books_by_author($_GET['author']));
  } else if (isset($_GET['readed'])) {
    echo json_encode($library->count_readed_book_by_user($_GET['userId']));
  } else if (isset($_GET['unread'])) {
    echo json_encode($library->count_unread_book_by_user($_GET['userId']));
  } else if (isset($_GET['reading'])) {
    echo json_encode($library->count_reading_book_by_user($_GET['userId']));
  } else if (isset($_GET['userId']) && !isset($_GET['create'])) {
    echo json_encode($library->find_books_in_library($_GET['userId']));
  } else if (isset($_GET['review'])) {
    echo json_encode($library->add_book_review($_GET['id'], $_GET['note'], $_GET['comment']));
  } else {
    echo json_encode($library->index());
  }
}

if (isset($_GET['wishlist'])) {
  $wishlist = new Wishlist;

  if (isset($_GET['create'])) {
    $wishlist->create($_GET['bookversion'], $_GET['userId']);
  } else if (isset($_GET['stat'])) {
    echo json_encode($wishlist->count_books_in_user_wishlist($_GET['userId']));
  } else if (isset($_GET['author'])) {
    echo json_encode($wishlist->count_books_by_author($_GET['author']));
  } else if (isset($_GET['userId']) && !isset($_GET['create']) && !isset($_GET['delete'])) {
    echo json_encode($wishlist->find_user_wishlist($_GET['userId']));
  } else if (isset($_GET['delete'])) {
    $wishlist->delete_from_user_wishlist($_GET['wishlist'], $_GET['userId']);
    echo 'wishlist deleted';
  } else {
    echo json_encode($wishlist->index());
  }
}
