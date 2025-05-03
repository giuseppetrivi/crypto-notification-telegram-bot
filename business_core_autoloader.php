<?php

spl_autoload_register(function($class) {
  $file = "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "config" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "exceptions" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "control" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "entities" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "entities/coinmarket_api" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});

spl_autoload_register(function($class) {
  $file = "view" . DIRECTORY_SEPARATOR . "{$class}.php";
  //this will check if file exist 
  if (is_file($file)) {
    //finally if file exist then it will include the file
    require_once $file;
  }
});