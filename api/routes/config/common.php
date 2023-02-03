<?php
  function sanitize($str) {
    $regex = '/SELECT|select|DROP|drop|DATABASE|database|INSERT|insert|TRUNCATE|truncate|CREATE|create|TABLE|table|ALTER|alter|UPDATE|update|/';
    $str = preg_replace( $regex, '', $str);
    $str = trim($str);
    $str = strip_tags ($str);
    return $str;

}

  function sanitize_space($str) {
    $regex = '/SELECT|select|DROP|drop|DATABASE|database|INSERT|insert|TRUNCATE|truncate|CREATE|create|TABLE|table|ALTER|alter|UPDATE|update|/';
    $str = preg_replace( $regex, ' ', $str);
    $str = trim($str);
    $str = strip_tags ($str);
    return $str;

}

