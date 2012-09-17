<?php


print_r(openpath('/home/ganji/template_test/css_selector',true));

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
            $a[$curpath][$file] = $aa[$file];
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
  closedir($handle);
  return $a;
}


?>
