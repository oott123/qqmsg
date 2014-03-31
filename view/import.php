<!DOCTYPE html>
<html>
<head>
	<title>导入数据</title>
</head>
<body>
	<form method="POST" enctype="multipart/form-data">
		数据表名：
		<select name="table_name">
			<?php foreach($tables_in_db as $table):?>
			<option value="<?php echo $table;?>"><?php echo $table;?></option>
			<?php endforeach;?>
		</select><br />
		数据文件：
		<select name="file_name">
			<?php foreach($files as $file):?>
			<option value="<?php echo $file;?>"><?php echo $file;?></option>
			<?php endforeach;?>
		</select><br />
		上传文件？
		<input type="file" name="upload" /><br /> 
		<input type="submit"></input>
	</form>
	<p><?php echo $text;?></p>
</body>
</html>