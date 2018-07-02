<?php

/**
 * Displays site name.
 */
function siteName()
{
  echo config('name');
}

/**
 * Displays site version.
 */
function siteVersion()
{
  echo config('version');
}

/**
 * Displays page title. It takes the data from
 * URL, it replaces the hyphens with spaces and
 * it capitalizes the words.
 */
function pageTitle()
{
  $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Home';

  echo ucwords(str_replace('-', ' ', $page));
}

function pageContent()
{
  $page = isset($_GET['page']) ? $_GET['page'] : 'home';

  $path = getcwd().'/'.config('content_path').'/'.$page.'.php';

  if (file_exists(filter_var($path, FILTER_SANITIZE_URL))) {
    include $path;
  } else {
    include config('content_path').'/404.php';
  }
}
function run()
{
  if (config('debug')) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
  } else {
    ini_set('display_errors', 'Off');
    error_reporting(0);
  }

  $dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
  if (!$dbcon) {
    die('Could not connect to database. Kindly check your credentials and try again.');
  } else {
    mysqli_close($dbcon);
    session_start();
    include config('template_path').'/template.php';
  }
}
