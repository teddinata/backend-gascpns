<?php

function formatDate($date) {
    if (is_null($date)) {
      return '-'; // Or any default string for missing dates
    }
    return $date->format('d/m/Y, H:i'); // Adjust format as needed
  }

?>
