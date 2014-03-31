<?php
	if(!defined('QQMSG_ROOT'))die('AD.');
	$table = isset($_GET['table'])?$_GET['table']:false;
	$contact = isset($_GET['contact'])?$_GET['contact']:false;
	$group = isset($_GET['group'])?$_GET['group']:false;
	$keyword = isset($_GET['keyword'])?$_GET['keyword']:false;
	$show_mid = isset($_GET['show_mid'])?$_GET['show_mid']:false;
	$where = array();
	//筛选
	if($contact){
		$contact = mysql_real_escape_string(htmlspecialchars($contact));
		$where[]='`contact` LIKE \''.$contact.'\'';
	}
	if($group){
		$group = mysql_real_escape_string(htmlspecialchars($group));
		$where[]="`group` LIKE '{$group}'";
	}
	if($keyword){
		$keyword = mysql_real_escape_string(htmlspecialchars($keyword));
		$where[]="`msg` LIKE '%{$keyword}%'";
	}
	if(count($where)>0){
		$where = implode(" AND ", $where);
		$where = ' WHERE '.$where;
	}else{
		$where = '';
	}
	$sql = 'SHOW TABLES';
	$res = $db->fetch_all($sql);
	$tables_in_db = array();
	if($res){
		foreach ($res as $row) {
			$tables_in_db[] = $row[0];
		}
	}
	if(!$table){
		//选择表
		include(QQMSG_ROOT.'/view/show_tables.php');
		die();
	}
	if(!in_array($table, $tables_in_db)){
		$table = $tables_in_db[0];
	}
	//好友列表
	$sql = "SELECT COUNT( * ) AS `num` , `contact` FROM `{$table}` GROUP BY `contact` ORDER BY `contact`";
	if($group){
		$sql = "SELECT COUNT( * ) AS `num` , `contact` FROM `{$table}` WHERE `group` LIKE '{$group}' GROUP BY `contact` ORDER BY `contact`";
	}
	$contact_list=$db->cache($sql,QQMSG_ROOT.CACHE_DIR,'fetch_all');
	//分组列表
	$sql = "SELECT COUNT( * ) AS `num` , `group` FROM `{$table}` GROUP BY `group` ORDER BY `group`";
	$group_list=$db->cache($sql,QQMSG_ROOT.CACHE_DIR,'fetch_all');
	//获取资源
	$count = 'SELECT COUNT(*) FROM `'.$table.'`'.$where;
	$count = $db->fetch($count);
	$count = $count[0];
	$num_pages = ceil($count/RECORD_PER_PAGE);
	$page = isset($_GET['page'])?$_GET['page']:$num_pages;
	$page=($page<1)?0:$page-1;
	$sql = 'SELECT * FROM `'.$table.'`'.$where.' ORDER BY `time` ASC LIMIT '.RECORD_PER_PAGE*$page.','.RECORD_PER_PAGE;
	if($show_mid){
		$sql = 'SELECT * FROM `'.$table.'`'.$where.' LIMIT '.RECORD_PER_PAGE*$page.','.RECORD_PER_PAGE;
	}
	$show_res = $db->fetch_all($sql);
	$page++;
	include(QQMSG_ROOT.'/view/show_log.php');
