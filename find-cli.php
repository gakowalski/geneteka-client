<?php

require 'functions.php';

// ask for first name
$name = readline('First name: ');

// ask for last name
$surname = readline('Last name: ');

// ask for region
$regions = geneteka_regions();
$region = readline('Region: ');
if (!empty($region) && !isset($regions[$region])) {
  echo 'Region not found';
  exit;
} else {
    $region = null;
    echo 'All regions' . PHP_EOL;
}

// ask for book
$books = geneteka_books();
$book = readline('Book: ');
if (!empty($region) && !isset($books[$book])) {
  echo 'Book not found';
  exit;
} else {
    $book = null;
    echo 'All books' . PHP_EOL;
}

// ask for starting date
$from = readline('From: ');

// ask for ending date
$to = readline('To: ');

// if empty, set to null
if (empty($from)) $from = null;
if (empty($to)) $to = null;

// search
$found = geneteka_find_person(
  $name,
  $surname,
  $region,
  $book,
  [
    'from' => $from,
    'to' => $to,
  ]
);