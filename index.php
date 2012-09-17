<?php
if(!preg_match("/^192\.168/",$_SERVER['REMOTE_ADDR']))
  exit;
define('PATH','/home/ganji/template_test/css_selector');


$html = headHTML();
$html .= bannerHTML();
$html .= bodyHTML();
$html .= footerHTML();


function makeTree($dir,$level=1,$path ="")
{
  
  $html = '<div class="accordion" id="accordion'.$level.'">';
  $i = 0;
  $in = 'in';
  if($level >1 )
    $in = '';
  foreach($dir as $key => $value)
  {
    $i++;
    if(is_array($value))
    {
      $html .= '<div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion'.$level.'" href="#collapse'.$level.$i.'">'.$key.'</a>
                  </div>
                  <div id="collapse'.$level.$i.'" class="accordion-body collapse '.$in.'">
                    <div class="accordion-inner">';
      $html .= makeTree($value,++$level,$path.$key."/");
      $html .='</div></div></div>';
      $in = "";
    }
    else{
      $html .= '<div class="accordion-group">
                  <div class="accordion-heading" style = "padding-left:15px;">';
      $p = PATH."/".$path.$key;
      $p = str_replace('dir/','',$p);
      if($value == 1)
        $html .='<a href="/tpl.php?filePath='.urlencode($p).'" style="color:#000000;" target="right">'.$key.'</a>';
      else
        $html .= '<a href="#" >'.$key.'(空)</a>';
      $html .='</div></div>';

    }
  }
  $html .='</div>';
  return $html;
}
echo $html;





function openpath($path=".",$ifchild=false,$curpath='dir'){
  $handle = opendir($path);
  $a = array();
  if($handle){
    $file = readdir($handle);
    while($file){
      if ($file != "." && $file != ".." && substr($file,0,1) != '.') {
        if(is_dir($path."/".$file)){
          if($ifchild){
            $aa = openpath($path."/".$file,$ifchild,$file);
            if(isset($aa[$file]))
            {
              $a[$curpath][$file] = $aa[$file];
            }
            else
            {
               $a[$curpath][$file]=2;
            }
            //print_r($aa);
          }else{
            $a[$curpath][$file]=1;
            //echo "<li><a href=\"$curpath/$file \" target=\"_blank\">$file</a></li>\n";
          }
        }else{
          $a[$curpath][$file] = 1;
        }
      }
      $file = readdir($handle);
    }
  }
  if(!empty($a[$curpath]))
    ksort($a[$curpath]);
  closedir($handle);
  return $a;
}



function headHTML()
{
  $header=<<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>模板测试后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  </head>
HTML;
  return $header;
}

function bannerHTML()
{
  $banner =<<<HTML
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/index.php">Ganji_Crawler</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
HTML;
  return $banner;
}
function bodyHTML()
{
  $body = <<<HTML
  <div class="container">
    <div class="container-fluid">
HTML;

  $body .= testHTML();

  $body .= <<<HTML
      <div class="row-fluid">
HTML;
  $body .= treeHTML();
  $body .= rightHTML();

  $body .=<<<HTML
      </div>
    </div>
  </div>
HTML;

  return $body;
}

function testHTML(){
  $test =<<<HTML
    <div>
      <iframe src = "/test.php" name = "test" style = 'width:100%;' id="test"  onload="this.height=test.document.body.scrollHeight" frameborder=0>
      </iframe>
    </div>
HTML;
  return $test; 

}
function treeHTML(){
  $dir = openpath(PATH,true);
  $tree = '<div class="span4">';
  $tree .='<div style="margin:5px;"><a href="/tpl.php?new=1" target="right" ><button class="btn btn-small btn-primary" type="button">New</button></a></div>';
  $tree .= makeTree($dir);
  $tree .='</div>';
  return $tree;
}
function rightHTML(){
  $right =<<<HTML
  <div class="span8" style = 'border:1px solid #000000;'>
  <iframe src="/tpl.php"  id ='right'  name = 'right' style = 'width:99%;' onload='this.height=right.document.body.scrollHeight' frameborder=0></iframe>
  </div>
HTML;
  return $right;
}

function footerHTML()
{
  $footer = <<<HTML
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
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

  </body>
</html>
HTML;
  return $footer;
}

?>
