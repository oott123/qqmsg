<?php
	if(!defined('QQMSG_ROOT'))die('AD.');
	$cache_dir=QQMSG_ROOT.'/cache';
	$cache_files=glob($cache_dir.'/*.cache.php');
	$count_success = 0;
	foreach ($cache_files as $cache) {
		if(unlink($cache)){
			$count_success++;
		}else{
			echo '无法清除缓存文件：'.$cache.'<br />';
		}
	}
	echo '清除成功，共有'.count($cache_files).'个缓存文件，成功清除'.$count_success.'个缓存文件。';
