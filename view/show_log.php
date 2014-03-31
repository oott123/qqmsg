<!DOCTYPE html>
<html>
<head>
	<title>查看记录</title>
	<style>
.msg{
	padding-left: 20px;
}
.sender{
	padding-left: 10px;
}
.msghead .same{
	color:#0058D4;
}
.msghead .notsame{
	color:#61862F;
}
a{
	text-decoration: none;
	color:#666;
}
a.msglink{
	color:#E96B0D;
}
a:hover{
	color:#888;
}
.hr{
	border-bottom: 1px solid #4285F4;
	margin: 20px;
	width: 150px;
	padding-bottom: 5px;
	text-align: center;
}
.highlight{
	background-color: #BD2D30;
	color:#fff;
}
.orgin{
	padding-right: 20px;
}
.msgentry{
	padding: 5px;
}
.msgentry:hover{
	padding: 4px;
	border:1px solid #7B8591;
	background-color: #D3E8FA;
}
.msgentry:hover .msgfwd{
	display: inline;
	padding-right: 15px;
	float: right;
}
.msgentry .msgfwd{
	display: none;
}
.highmsg{
	background-color: #72B6F8;
}
.num{
	float: right;
}
	</style>
</head>
<body>
	<table>
		<tr valign="top" id="msgtr">
		<td>
			<a href="?c=clearcache">清除缓存</a><br />
			<a href="?c=import">导入数据</a>
			<form method="GET" action="">
				<input type="hidden" name="table" value="<?php echo $table;?>"></input>
				<input type="hidden" name="contact" value="<?php echo $contact;?>"></input>
				<input type="hidden" name="group" value="<?php echo $group;?>"></input>
				<input type="text" name="keyword" />
				<input type="submit" value="搜索" />
			</form>
		数据表：<br />
		<?php foreach($tables_in_db as $row):?>
			<a href="<?php echo "?c=show&table={$row}"; ?>"><?php echo $row;?></a>
		<br />
		<?php endforeach;?>
			<a href="?c=add_table">[增加]</a><br />
		分组：<br />
		<?php foreach($group_list as $row):?>
			<a href="<?php echo "?c=show&table={$table}&group={$row['group']}"; ?>"><?php echo $row['group'];?></a> <?php echo $row['num'];?>
		<br />
		<?php endforeach;?>
		好友：<br />
		<?php foreach($contact_list as $row):?>
			<a href="<?php echo "?c=show&table={$table}&contact={$row['contact']}"; ?>"><?php echo $row['contact'];?></a> <?php echo $row['num'];?>
		<br />
		<?php endforeach;?>

		</td>
		<?php /*
		<td><table>
			<tr>
				<th>消息发送时间</th>
				<th>消息对象</th>
				<th>消息分组</th>
				<th>发送者</th>
				<th>消息内容</th>
			</tr>
			<?php foreach($show_res as $row): ?>
			<tr>
				<td><?php echo date('Y-m-d H:i:s',$row['time']);?></td>
				<td><a href="<?php echo "?c=show&table={$table}&contact={$row['contact']}"; ?>">
					<?php echo $row['contact'];?>
				</a></td>
				<td><a href="<?php echo "?c=show&table={$table}&group={$row['group']}"; ?>">
					<?php echo $row['group'];?>
				</a></td>
				<td><?php echo $row['sender'];?></td>
				<td><?php echo $row['msg'];?></td>
			</tr>
			<?php endforeach;?>
		</table></td>
		*/ ?>
		<td>
		<?php $lasttime = 0;foreach($show_res as $row): ?>
		<?php
			$same = ' notsame';
			if($row['sender'] == $row['contact']){
				$same = ' same';
			}
			//关键字高亮
			$msg = $row['msg'];
			if($keyword){
				$msg = preg_replace('`'.preg_quote($keyword).'`i','<span class="highlight">\0</span>', $msg);
			}else{
				//由于和上面冲突，于是这里处理消息记录中的链接
				$msg = preg_replace('/http\:\/\/([a-z0-9\#\@\:\%.\/_\?\&\+\=\*\!\~\;\|\[\]\-\(\)]*)?/i', '<a href="\0" class="msglink" target="_blank">\0</a>', $msg);
			}
			if(date('Y-m-d',$lasttime)!=date('Y-m-d',$row['time'])){
				echo "<div class='hr'>".date('Y-m-d',$row['time'])."</div>";
			}
			if($show_mid == $row['mid']){
				$same.=' highmsg';
			}
		?>
		<div class="msgentry<?php echo $same;?>" id="msg<?php echo $row['mid'];?>">
			<div class="msghead<?php echo $same;?>"><span class="time<?php echo $same;?>"><?php echo date('H:i:s',$row['time']);?></span> 
			<span class="sender<?php echo $same;?>"><?php echo $row['sender'];?></span>
			<?php //if($keyword){
				$topage = ceil($row['mid']/RECORD_PER_PAGE);
				echo "<span class='msgfwd'><a href='?c=show&page={$topage}&table={$table}&show_mid={$row['mid']}#msg{$row['mid']}'>查看前后消息</a></span>";
			//}
			?>
			</div>
			<div class="msg<?php echo $same;?>">
				<?php echo $msg;?>
			</div>
		</div>
		<?php $lasttime = $row['time'];?>
		<?php endforeach;?>
		</td>
	</table>

	<p>
		总计<?php echo $count;?>条记录 <?php echo $page.'/'.$num_pages;?>
		<?php for($i=1;$i<=$num_pages;$i++){
			$j = $i;
			if($i == $num_pages){
				$j='末页';
			}elseif ($i == 1) {
				$j='第一页';
			}elseif($page-$i==1){
				$j='上一页';
			}elseif($page-$i==-1){
				$j='下一页';
			}elseif(abs($page-$i)==5){
				$j='...';
			}
			if($page == $i){
				echo $j;continue;
			}elseif(abs($page-$i)>5 && abs($num_pages-$i)>5 && $i>5){
				continue;
			}
			echo ' <a href="?c=show&table='.$table.'&page='.$i."&table={$table}&contact={$contact}&group={$group}&keyword={$keyword}&show_mid={$show_mid}".'">'.$j.'</a> ';
		}
		?>
	</p>
</body>
</html>