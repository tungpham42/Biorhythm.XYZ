<?php
header("Content-Type: application/javascript");
$files = array(
  'google_analytics' => 'https://www.google-analytics.com/analytics.js',
  'google_adsense' => 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
  'google_font' => 'https://fonts.googleapis.com/css?family=Comfortaa',
  'highchart_exporting' => 'https://code.highcharts.com/modules/exporting.js',
  'highchart' => 'https://code.highcharts.com/highcharts.js'
);

if(isset($files[$_GET['file']])) {
  if ($files[$_GET['file']] == 'google_analytics'){
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + ((60 * 60) * 24 * 7))); // 2 days for GA
  } else {
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + ((60 * 60) * 24 * 7))); // Default set to 1 hour
  }
  echo file_get_contents($files[$_GET['file']]);
}

?>