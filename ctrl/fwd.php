<?php
	if(!defined('QQMSG_ROOT'))die('AD.');
	/*
		查询前后消息记录 思路提示
		1.如果没有的话，创建一个“fwd_temp”的表
		2.将相关条件（符合分组、联系人）的对话全部导入fwd_temp表（可能需要较长时间）
		3.查询相关信息对应的表所在的mid，找到对应分页，跳转到view查看

		由于实现太过复杂，因此仅在对应表中取得页码后即跳转相关分页
	*/
	