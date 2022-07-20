<?php


 $existing_alerts = array(
     array(
         'id' => '1',
         'time' => '2022-07-19 13:54:30',
         'status' => 'active',
         'type' => 'notice',
         'source' => 'little_forest',
         'url' => 'https://decubing.com',
         'message' => '[code]<title>...</title>[/code]Check that!',
         'meta' => 'a:2:{s:9:"guideline";s:43:"WCAGtle";}'
     ),
     array(
         'id' => '2',
         'time' => '2022-07-19 13:54:30',
         'status' => 'active',
         'type' => 'notice',
         'source' => 'little_forest',
         'url' => 'https://equalify.app',
         'message' => '[code]<title>...</title>[/code]Check that!',
         'meta' => 'a:2:{s:9:"guiitle";}'
     )
 );

 $new_alerts = array(
     array(
         'status' => 'active',
         'type' => 'notice',
         'source' => 'little_forest',
         'url' => 'https://wpcampus.org',
         'message' => '[code]<title>...</title>[/code]Check that!',
         'meta' => 'a:2:{s:9:"guideline";s:43:"WCAGtle";}'
     ),
     array(
         'status' => 'active',
         'type' => 'notice',
         'source' => 'little_forest',
         'url' => 'https://equalify.app',
         'message' => '[code]<title>...</title>[/code]Check that!',
         'meta' => 'a:2:{s:9:"guiitle";}'
     )
 );

 function make_alert_key($alert) {
     return json_encode(array(
         $alert['type'],
         $alert['source'],
         $alert['url'],
         $alert['message']
     ));
 }

function get_duplicate_alerts(&$existing_alerts, &$new_alerts, &$duplicate_alerts) {
  for ($i = 0; $i < count($new_alerts); $i++) {
    $alert = $new_alerts[$i];
    $key = make_alert_key($alert);
    $keys[$key] = $i;
  }
  for ($j = 0; $j < count($existing_alerts); $j++) {
    $alert = $existing_alerts[$j];
    $key = make_alert_key($alert);
    if (isset($keys[$key])) {
      $i = $keys[$key];
      $duplicate_alerts[] = $alert;
      unset($existing_alerts[$j]);
      unset($new_alerts[$i]);
    }
  }
  // Reset array indices after removing duplicates
  $existing_alerts = array_values($existing_alerts);
  $new_alerts = array_values($new_alerts);
}

$duplicate_alerts = [];
get_duplicate_alerts($existing_alerts, $new_alerts, $duplicate_alerts);

echo json_encode(array(
  'existing alerts' => $existing_alerts,
  'new alerts' => $new_alerts,
  'duplicate alerts' => $duplicate_alerts,
), JSON_PRETTY_PRINT);