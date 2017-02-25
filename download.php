<?php

$s_file = $_SERVER['DOCUMENT_ROOT'] . "/dir/" . $_GET['file'];
$file = $_SERVER['DOCUMENT_ROOT'] . '/dir/hashlist.txt';

$check = FALSE;

$hash = $_GET['hash'];

if(strlen($hash) != 32) {
	exit("Error hash!");
}

$arr = file($file);

$fd = fopen($file,"w");
if(!$fd) {
	exit("Не возможно открыть файл");
}

if(!flock($fd,LOCK_EX)) {
	exit("Блокировка файла не удалась");
}

for ($i = 0; count($arr) > $i; $i++) {
	
	if($hash == rtrim($arr[$i])) {
		
		$check = TRUE;
	}
	else {
		fwrite($fd,$arr[$i]);
	}
}

if(!flock($fd,LOCK_UN)) {
	exit("Не возможно разблокировать файл");
}
fclose($fd);

if($check) {
	header("Content-Description: File Transfer");
	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=".basename($s_file));
	header("Content-Transfer-Encoding:binary");
	
	header("Content-Length: ".filesize($s_file));
	
	ob_clean();
	flush();
	
	readfile($s_file);
	exit();
}
else {
	exit("Error link!");
}


?>