 <h5><strong>백업<strong><h5>
 <a class="w3-btn w3-theme-l5" style="100%" href="index.php?id=Admin_Main&admin=Admin_Backup&del=N">백업 데이터베이스 생성하기</a><br><br>
 <a class="w3-btn w3-theme-l5" style="100%" href="index.php?id=Admin_Main&admin=Admin_Backup&del=Y">서버에 저장된 백업 데이터베이스 지우기</a>

<?php
// 권한 관리
require_once 'php/user_chk.php';

if($_GET[del]==Y)
array_map('unlink', glob("*.sql"));
else if($_GET[del]==N)
backup_tables('localhost','<YOUR_DB_ID>','<YOUR_DB_PW>','<YOUR_DB_TABLE>');

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{

	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);

	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);

		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";

		for ($i = 0; $i < $num_fields; $i++)
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j < $num_fields; $j++)
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j < ($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	//save file
  $file_name = 'blms-db-backup['.date( 'Y-m-d_H:i:s', time() ).'].sql';
	$handle = fopen($file_name,'w+');
	fwrite($handle,$return);
	fclose($handle);
  echo '<br><br><a class="w3-btn w3-btn w3-theme-l5" style="100%" href="'.$file_name.'">다운로드 : '.$file_name.'</a>';
}
 ?>
