<?php
  // Google Application Credential path location
  // use dinamic path, base on current working direcory
  $credentialPath = getcwd() . '/key1.json';

  //or use full path
  // $credentialPath = '/opt/lampp/htdocs/vision/key1.json';

  // Database configuration
  $db = array (
    'host' => 'localhost',
    'port' => '3306',
    'username' => 'root',
    'password' => '',
    'database' => 'vision'
  );

  // Load the configuration
  putenv("GOOGLE_APPLICATION_CREDENTIALS=" . $credentialPath);
  return (object) array (
    'db' => $db
  );
 ?>
