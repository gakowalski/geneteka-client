<?php

function geneteka_url($query) {
  if (is_array($query)) $query = http_build_query($query);
  return "http://geneteka.genealodzy.pl/index.php?$query";
}

function geneteka_api_url($query) {
  if (is_array($query)) $query = http_build_query($query);
  return "http://geneteka.genealodzy.pl/api/getAct.php?$query";
}
