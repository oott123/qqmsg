<?php
	if(!defined('QQMSG_ROOT'))die('AD.');
	$sql = 'SHOW TABLES';
	$res = $db->fetch_all($sql);
	$text='导入数据可能需要较长时间。上传文件优先选择文件。';
	$tables_in_db = array();
	if($res){
		foreach ($res as $row) {
			$tables_in_db[] = $row[0];
		}
	}
	$table = isset($_POST['table_name'])?$_POST['table_name']:false;
	if(!in_array($table, $tables_in_db)){
		$table = $tables_in_db[0];
	}
	$files = glob(QQMSG_ROOT.'/raw/*.txt');
	if(isset($_POST['table_name'])){
		//开始导入数据
		@set_time_limit(0);
		@ignore_user_abort();
		$filename = $_FILES['upload']['tmp_name']?$_FILES['upload']['tmp_name']:$_POST['file_name'];
		$file = fopen($filename,'rb');
		$line_num = 0;
		$current_time = 0;
		$current_sender = '';
		$current_contact = '';
		$current_group = '';
		$current_msg = '';
		$last_line = '';
		while(!feof($file)){
			//if($line_num>50)break;
			$line_num++;
			$line = trim(fgets($file));
			if(preg_match('/^(\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}:\d{2}) (.*)$/', $line,$matches) && !$last_line){
				//确认一条消息记录
				if($current_time){
					//保存上一条消息记录
					insert_msg($table,$current_time,$current_sender,$current_contact,$current_group,$current_msg,$db);
				}
				$current_msg = '';
				$current_time = strtotime($matches[1]);
				$current_sender = $matches[2];
			}elseif($line == '================================================================'){
				//保存上一条消息记录
				if($current_time){
					//保存上一条消息记录
					insert_msg($table,$current_time,$current_sender,$current_contact,$current_group,$current_msg,$db);
				}
				$current_msg = '';
				$current_time = 0;
				$current_sender = '';
			}elseif($last_line == '================================================================' && preg_match('/^消息分组:(.*)$/', $line, $matches)){
				//获得消息分组
				$current_group = $matches[1];
			}elseif($last_line == '================================================================' && preg_match('/^消息对象:(.*)$/', $line, $matches)){
				//获得消息对象
				$current_contact = $matches[1];
			}elseif($line){
				$current_msg.=$line."\n";
			}
			$last_line = $line;
		}
		//确认消息记录
		if($current_time){
			insert_msg($table,$current_time,$current_sender,$current_contact,$current_group,$current_msg,$db);
		}
		$text='消息处理完毕。<a href="?c=clearcache">清除缓存</a>';
	}
	include(QQMSG_ROOT.'/view/import.php');

	function insert_msg($table,$time,$sender,$contact,$group,$msg,$db){
			//echo 'A message from '.$sender.' should be logged as '.$msg.' at '.$time.' in '.$table."<br />";
		$msg = trim($msg);
		$msg = htmlspecialchars($msg);
		$msg = nl2br($msg);
		$sender = htmlspecialchars($sender);
		$contact = htmlspecialchars($contact);
		$group = htmlspecialchars($group);
		$msg = mysql_real_escape_string($msg);
		$sender = mysql_real_escape_string($sender);
		$contact = mysql_real_escape_string($contact);
		$group = mysql_real_escape_string($group);
		$sql = 'INSERT INTO `'.$table.'` (`time`, `sender`, `msg`, `contact`, `group`) VALUES (\'%d\', \'%s\', \'%s\', \'%s\', \'%s\')';
		$sql = sprintf($sql,$time,$sender,$msg,$contact,$group);
		$db->query($sql);
	}
