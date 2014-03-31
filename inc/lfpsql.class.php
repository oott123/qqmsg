<?php
	/*
		LFP数据库类
		功能：连接数据库
	*/
	class LFPSql{
		var $con;
		var $debug=false;
		function __construct($dbhost,$dbuser,$dbpass,$dbname){
			global $_g;
			if(is_array($_g)){
				if(isset($_g['debug'])){
					$this->debug=$_g['debug'];
				}
			}
			$this->con=mysql_connect($dbhost,$dbuser,$dbpass);
			$this->debug("SQL数据库连接：$dbuser : $dbpass @ $dbhost");
			$this->iserror();
			mysql_select_db($dbname,$this->con);
			$this->debug("SQL数据库选择：$dbname");
			$this->iserror();
		}//构造函数
		function query($sql){
			$this->debug('SQL请求：'.$sql);
			$time1 = microtime(1);
			$res=mysql_query($sql,$this->con);
			$this->iserror();
			$this->debug('SQL请求时间：'.(microtime(1)-$time1));
			return $res;
		}//执行函数
		function num($sql){
			$this->debug('计数请求：'.$sql);
			$time1 = microtime(1);
			$res=$this->query($sql);
			$num=mysql_num_rows($res);
			$this->iserror();
			$this->debug('计数时间：'.(microtime(1)-$time1));
			return $num;
		}//检查数目
		function fetch_all($sql){
			$time1 = microtime(1);
			$res=$this->query($sql);
			$arr=array();
			while($row = mysql_fetch_array($res)){
				$this->iserror();
				$arr[]=$row;
			}
			$this->debug('返回全部数据时间：'.(microtime(1)-$time1));
			return $arr;
		}//返回全部数组
		function cache($sql,$cache_dir,$method){
			$cache_file = $cache_dir.'/'.md5($sql).'.cache.php';
			$return = false;
			if(is_file($cache_file)){
				$this->debug('缓存命中：'.$sql);
				$return=unserialize(base64_decode(include($cache_file)));
			}else{
				$this->debug('缓存没有命中：'.$sql);
				$return=$this->$method($sql);
				$data = base64_encode(serialize($return));
				$file_data = '<?php return "'.$data.'";';
				file_put_contents($cache_file, $file_data);
			}
			return $return;
		}
		function fetch($sql){
			$time1 = microtime(1);
			$res=$this->query($sql);
			$row=mysql_fetch_array($res);
			$this->iserror();
			$this->debug('返回单行数据时间：'.(microtime(1)-$time1));
			return $row;
		}//返回单行
		function insertid(){
			$id=mysql_insert_id($this->con);
			$this->iserror();
			return $id;
		}//插入ID
		function iserror(){
			//报错函数
			if(mysql_errno()!=0){
				if(function_exists('fr_end')){
					fr_end('LFP遇到一个致命的数据库错误 <br /> Error ' . mysql_errno() . '#:' . mysql_error());
				}else{
					die('SQL Error '. mysql_errno() . '#:' . mysql_error());
				}
			}
		}//报错函数，于每次执行后调用，防止错误。
		function debug($msg, $object = false) {//调试函数
	        if ($this->debug) {
	            print '<div style="border: 1px solid red; padding: 0.5em; margin: 0.5em;"><strong>LFP  Debug:</strong> '.$msg;
	            if ($object) {
	        	    $content = htmlentities(print_r($object,true),ENT_COMPAT,'UTF-8');
	        	    print '<pre>'.$content.'</pre>';
	        	}
	        	print '</div>';
	        }
    	}//调试函数
	}