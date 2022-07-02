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

/*
  Search for a person.

  Required: name, surname, region.

  $options array:
    name          - 'John'
    surname       - 'Doe'
    record_type   - 'birth' or 'marriage' or 'death'
    regions       - eg. 'mazowieckie'
    from          - eg. 1823
    to            - eg. 1864
*/
function geneteka_search($options) {
  $regions = geneteka_regions();
  $books = geneteka_books();

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
    'start' => 0,
    'length' => 10,
  );

  if (isset($options['name'])) {
    $query['search_name'] = $options['name'];
  }

  if (isset($options['surname'])) {
    $query['search_lastname'] = $options['surname'];
  }

  if (isset($options['record_type'])) {
    if (isset($books[$options['record_type']])) {
      $query['bdm'] = $books[$options['record_type']];
      $query['rid'] = $books[$options['record_type']];
    } else {
      echo 'NOT SUPPORTED RECORD TYPE: ' . $options['record_type'];
    }
  }

  if (isset($options['region'])) {
    if (isset($regions[$options['region']])) {
      $query['w'] = $regions[$options['region']];
    } else {
      echo 'NOT SUPPORTED: all regions';
    }
  } else {
    echo 'NOT SUPPORTED: no region specified';
  }

  if (isset($options['from'])) {
    $query['from_date'] = $options['from'];
  }

  if (isset($options['to'])) {
    $query['to_date'] = $options['to'];
  }

  $url = geneteka_api_url($query);
  $str = file_get_contents($url, context: stream_context_create([
    'ssl' => [
      'verify_peer'=> false,
      'verify_peer_name'=> false,
    ],
  ]));

  return json_decode($str, true);
}

function geneteka_if_exists($options) {
  return geneteka_search($options)['recordsTotal'] > 0;
}

function geneteka_find_person($name, $surname, $book = null, $region = null, $options = []) {
  $books = $book ? [ $book => 0 ] : geneteka_books();
  $regions = $region ? [ $region => 0 ] : geneteka_regions();
  $results = [];

  $search_array = [
    'name' => $name, 
    'surname' => $surname, 
  ] + $options;

  $count = 0;
  $max_count = count($books) * count($regions);

  foreach ($regions as $_region => $rcode) {
    foreach ($books as $_book => $bcode) {
      ++$count;
      echo ">> [$count/$max_count] checking $_region $_book... \t";
      $found = geneteka_search($search_array + [
        'region' => $_region, 
        'record_type' => $_book
      ]);
      if (isset($found['recordsTotal']) && $found['recordsTotal'] > 0) {
        echo "found {$found['recordsTotal']} records!\n";
        if (isset($results[$_region]) === false) $results[$_region] = [];
        $results[$_region][$_book] = 1;
      } else {
        echo "nothing found.\n";
      }
      //sleep(1);
      usleep(125000); // 0.125 sec
    }
  }

  return $results;
}
