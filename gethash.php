<?php

$hash = md5(microtime());
$dir = $_SERVER['DOCUMENT_ROOT'] . '/dir';
$file = $_SERVER['DOCUMENT_ROOT'] . '/dir/hashlist.txt';
$arr = [];

if($handle = opendir($dir)){

    while(false !== ($arch = readdir($handle))) {
        if($arch != "." && $arch != ".." && $arch != "hashlist.txt" && $arch != ".htaccess"){
        $arr[] = $arch;
        }
    }
}

$fd = fopen($file,"a");
if(!$fd) {
	exit("Не возможно открыть файл");
}
if(!flock($fd,LOCK_EX)) {
	exit("Блокировка файла не удалась");
}
fwrite($fd,$hash."\n");

if(!flock($fd,LOCK_UN)) {
	exit("Не возможно разблокировать файл");
}
fclose($fd);

$path = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")) . '/audio';

foreach ($arr as $key) {
  //$hash = md5(microtime());
  echo "<p><b>".$key."</b> - <br>http://". $_SERVER['HTTP_HOST'].$path."/download.php?hash=".$hash."&file=".$key."</p>";
}

?>