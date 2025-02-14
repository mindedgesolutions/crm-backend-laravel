<?php

function getFirstLetters($string)
{
  $words = explode(' ', $string);
  $letters = '';
  foreach ($words as $word) {
    $letters .= strtolower($word[0]);
  }
  return $letters;
}
