<?php
	if(!defined('QQMSG_ROOT'))die('AD.');
	$text = '';
	if(isset($_POST['table_name'])){
		$table_sql = str_replace('<table_name>', $_POST['table_name'], $table_sql);
		$db->query($table_sql);
		$text = '添加数据表成功，请<a href="?c=view">返回</a>查看，或者<a href="?c=import">导入数据</a>。';
	}

	include(QQMSG_ROOT.'/view/add_table.php');
