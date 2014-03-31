<?php
	session_start();
	require('./inc/config.inc.php');
	define('QQMSG_ROOT', dirname(__FILE__));
	if(isset($_POST['pwd'])){
		//登录处理
		$_SESSION['pwd'] = $_POST['pwd'];
	}
	if(!isset($_SESSION['pwd']) || $_SESSION['pwd']!=AUTH_PW){
		//没有登录
		require('./view/login.php');
		die();
	}
	//连接数据库
	require('./inc/lfpsql.class.php');
	$db = new LFPSql(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$db->query("SET NAMES 'UTF8'");
	//交给控制器处理吧……
	$allow_controller = array('view','add_table','import','clearcache','fwd');
	if(!isset($_GET['c']) || !in_array($_GET['c'], $allow_controller)){
		$_GET['c'] = $allow_controller[0];
	}
	$ctrl = $_GET['c'];
	require('./ctrl/'.$ctrl.'.php');
