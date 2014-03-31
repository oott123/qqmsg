<!DOCTYPE html>
<html>
<head>
	<title>选择要浏览的数据表</title>
</head>
<body>
	<h1>请选择要浏览的数据表</h1>
	<ul>
		<?php foreach($tables_in_db as $table):?>
		<li><a href="?c=show&table=<?php echo $table;?>"><?php echo $table;?></a></li>
		<?php endforeach;?>
	</ul>
</body>
</html>