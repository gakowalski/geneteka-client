<?php

function geneteka_url($query) {
  if (is_array($query)) $query = http_build_query($query);
  return "http://geneteka.genealodzy.pl/index.php?$query";
}
