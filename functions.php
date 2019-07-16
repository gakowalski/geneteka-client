<?php

require 'config.php';

function geneteka_regions() {
  return [
    'dolnośląskie' => '01ds',
    'kujawsko-pomorskie' => '02kp',
    'lubelskie' => '03lb',
    'lubuskie' => '04ls',
    'łódzkie' => '05ld',
    'małopolskie' => '06mp',
    'mazowieckie' => '07mz',
    'Warszawa' => '71wa',
    'opolskie' => '08op',
    'podkarpackie' => '09pk',
    'podlaskie' => '10pl',
    'pomorskie' => '11pm',
    'śląskie' => '12sl',
    'świętokrzyskie' => '13sk',
    'warmińsko-mazurskie' => '14wm',
    'wielkopolskie' => '15wp',
    'zachodniopomorskie' => '16zp',
    'Ukraina' => '21uk',
    'Białoruś' => '22br',
    'Litwa' => '23lt',
    'Pozostałe' => '25po',
  ];
}

function geneteka_books() {
  return [
    'birth' => 'B',
    'marriage' => 'S',
    'death' => 'D',
  ];
}

function geneteka_if_exists($options) {
  $regions = geneteka_regions();

  $query = array(
    'op' => 'gt', //< gt = geneteka
    'lang' => 'pol',
    'bdm' => 'B', //< births
    'rid' => 'B', //< parish ID *or* the same value as 'bdm' to denote all
    'search_lastname' => '',
    'search_name' => '',
    'search_lastname2' => '',
    'search_name2' => '',
    'from_date' => '',
    'to_date' => '',
    'exac' => 1, //< strict search
  );

  if (isset($options['name'])) {
    $query['search_name'] = $options['name'];
  }

  if (isset($options['surname'])) {
    $query['search_lastname'] = $options['surname'];
  }

  if (isset($options['region'])) {
    if (isset($regions[$options['region']])) {
      $query['w'] = $regions[$options['region']];
    } else {
      echo 'NOT SUPPORTED: all regions';
    }
  }

  $url = geneteka_url($query);
  $str = file_get_contents($url);
  $re = '/Pozycje od \d+ do \d+ z \d+/m';

  //return preg_match($re, $str) === 1;
  return $str;
}
