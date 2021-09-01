<?php
DEFINE ('ID', 'usadmin');

if (isset($_POST['do'])) {
	$link = mysqli_connect('localhost', 'root', '') or die('Ошибка соединения: ' . mysqli_error());
	$link->select_db('contest');
	$do = $_POST['do'];
	switch ($do) {
		case 'init':
			$files = scandir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ID);
			foreach ($files as $key => $file) {
				if ($key > 1) {
					echo "<a class='file' data-file='$file'>" . $file . "</a>";
				}
			}
			break;
		case 'read':
			$file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ID . DIRECTORY_SEPARATOR . $_POST['file'];
			if (file_exists($file) && $contents = @file($file)) {
				if ($_POST['file']===basename(__FILE__)) {
					echo "Листинг этого файла пока скрыт";
				} else {
					foreach ($contents as $content) {
						echo "<li>" . str_ireplace(["&Tab;", ' '], ["<tab>", '&nbsp;'], htmlentities($content, ENT_HTML5)) . "</li>";
					}
				}
			} else {
				echo 'Нет доступа к "' . $_POST['file'] . '"';
			}
			break;
		case 'insert':
			$inserted = insert($link);
			if ($inserted !== true) {
				echo $inserted;
			}
			break;
		case 'new':
			if (!($res = mysqli_query($link, "DROP TABLE IF EXISTS " . ID))) {
				echo "Ошибка запроса: " . mysqli_error($link);
			}
			break;
		case 'get':
			$id = (int)$_POST['id'];
			if ($id > 0) {
				$sql = "SELECT value FROM " . ID . " WHERE id=$id";
				if (!($res = mysqli_query($link, $sql)))
					echo "Ошибка запроса: " . mysqli_error($link);
				if ($arr = mysqli_fetch_row($res)) {
					echo $arr[0];
				} else {
					echo "id не найден!";			
				}
			} else {
				echo "Недопустимый id!";
			}
	}
	mysqli_close($link);
}

function createTable($link) {
	if ($res = mysqli_query($link, "SHOW TABLES LIKE '" . ID . "'")) {
		if (empty(mysqli_fetch_array($res))) {
			$sql = "CREATE TABLE " . ID . " (
				  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				  value INT UNSIGNED NOT NULL,
				  PRIMARY KEY (id));";
			if (mysqli_query($link, $sql)) {
				return true;
			}
		} else {
			return true;
		}
	}
	return 'Ошибка при создании таблицы: ' . mysqli_error($link);
}

function insert($link) {
	$tb_st = createTable($link);
	if ($tb_st !== true) {
		return $tb_st;
	} else {
		$res_value = mysqli_query($link, "SELECT COUNT(*) max FROM " . ID);
		$row_value = mysqli_fetch_assoc($res_value);
		$max_id = $row_value['max'];
		if ($max_id >= 1000000) {
			return true;
		} else {
			$table = ID;
			$sqls = [
				"INSERT INTO $table (value) VALUES (0),(0),(0),(0),(0),(0),(0),(0),(0),(0)",
				"INSERT INTO $table SELECT NULL, t1.VALUE FROM $table AS t1, $table AS t2, $table AS t3, $table AS t4, $table AS t5, $table AS t6",
				"UPDATE $table SET `value` =
					(CASE MOD(id,3)
						WHEN 0 THEN 8
						WHEN 1 THEN 11
						WHEN 2 THEN 5
					END)",
				"UPDATE $table SET value = (CASE id
					 WHEN 1 THEN 7
					 WHEN 2 THEN 14
					 WHEN 3 THEN 17
					 WHEN 4 THEN 20
				   END) WHERE id<5;",
			];
			foreach($sqls as $sql) {
				if (!mysqli_query($link, $sql)) {
					return "Ошибка запроса: " . mysqli_error($link);
				}
			}
			return true;
		}
	}
}
?>