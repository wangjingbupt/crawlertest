<?php
error_reporting(0);
ini_set('display_errors',0);
include('call.php');

$template = urldecode($_GET['template']);
$site = urldecode($_GET['site']);
$depth = intval($_GET['depth']);


//$filePath = str_replace('/home/ganji','../..',$filePath);
//$filePath = '/home/ganji/template_test/css_selector/baixing.tar.bz2';
//$fp = fopen($filePath)
if($template != ""  && $site !="")
{
  $siteSign = true;
  $siteHtml = file_get_contents($site);
/*  if($siteHtml)
  {
    $fp = fopen('/tmp/testSite.html','w');
    fwrite($fp,$siteHtml);
    fclose($fp);
  }
  else
  {
    $siteSign = false;
  }
 */
  //$path = "/home/ganji/";
  //$conf = $path."conf.txt";
  $call = new call();
  $out = $call->call_extract($template,$depth,$siteHtml,$site);
  // $cmd = "{$path}test_extractor {$conf} {$template} {$depth} /tmp/testSite.html";
  // exec($cmd,$out);
  if(is_array($out))
  {
    $outs .= '<pre class="prettyprint linenums" style="margin-bottom: 9px;margin-top:20px;">';
    foreach($out as $line)
    {
      $line = htmlentities($line,ENT_NOQUOTES,'UTF-8');
      $outs .=$line."\n";

    }
    $outs .=' </p>';
    
  }
  

}

$html = head();
$html .=<<<HTML
  <body>
  <div class="container">
HTML;
$html .= form($template,$depth,$site);
if(isset($outs))
{
  $html .=<<<HTML
  <div>
    $outs;
  </div>
HTML;
}
$html .=<<<HTML
  </div>
  </body>
HTML;


echo $html;
function form($template,$depth,$site){
  if($template == "")
  {
  $html =<<<HTML
<div>
<form class="form-inline" src ="/test.php" target="test">
  <input type="text" placeholder="template" name="template" > 
  <input type="text" class="input-small" placeholder="depth" name="depth" > 
  <input type="text" class="input-xxlarge" placeholder="Input site URL" name="site" >
  <button type="submit" class="btn btn-mini">Submit</button>
</form>
</div>
HTML;
  }
  else
{

  $html =<<<HTML
<div>
<form class="form-inline" src ="/test.php" target="test">
  <input type="text" value="$template"  name="template" >
  <input type="text" class="input-small"  value="$depth" name="depth" >
  <input type="text" class="input-xxlarge" value="$site" name="site" >
  <button type="submit" class="btn btn-mini">Submit</button>
</form>
</div> 
HTML;

}

  return $html;
}

function head(){

  $html =<<<HTML
<html>
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="js/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
    <script src="js/jquery.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script src="js/bootstrap-affix.js"></script>
    <script src="js/application.js"></script>
<body>
HTML;
    return $html;
}


?>
