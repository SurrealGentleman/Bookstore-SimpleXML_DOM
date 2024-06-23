<?
	session_start();
	unset($_SESSION['idDEP']);
	unset($_SESSION['count1']);
	$xml = simplexml_load_file('../xml/index.xml') or die('Не удалось создать объект');
	echo "<h1>{$xml['title']}</h1>";
	$nodes = $xml->department;
	$count = count($nodes);
	for ($j=0; $j < $count; $j++) { 
		echo "<h2>Отдел: {$xml->department[$j]['name']}</h2>";
		$nodes1 = $xml->department[$j]->book;
		$count1 = count($nodes1);
		for ($i=0; $i < $count1; $i++) { 
			echo "<ul>";
			echo "<li>Книга: {$nodes1[$i]['name']} - {$xml->department[$j]->book[$i]['year']}, {$xml->department[$j]->book[$i]['pages']}</li>";
			echo "<ul>";
			
			$nodes2 = $xml->department[$j]->book[$i]->genre;
			$count2 = count($nodes2);
			$arr = array();
			for ($k=0; $k < $count2; $k++) { 
				array_push($arr, $xml->department[$j]->book[$i]->genre[$k]['name']);
			}
			$str_info = implode(', ', $arr);
			echo "<li>Жанр: {$str_info}</li>";

			$nodes2 = $xml->department[$j]->book[$i]->autor;
			$count2 = count($nodes2);
			$arr = array();
			for ($k=0; $k < $count2; $k++) { 
				array_push($arr, $xml->department[$j]->book[$i]->autor[$k]['full_name']);
			}
			$str_info = implode(', ', $arr);
			echo "<li>Автор: {$str_info}</li>";

			$nodes2 = $xml->department[$j]->book[$i]->location;
			$count2 = count($nodes2);
			$arr = array();
			for ($k=0; $k < $count2; $k++) { 
				array_push($arr, $xml->department[$j]->book[$i]->location[$k]['name']);
			}
			$str_info = implode(', ', $arr);
			echo "<li>Расположение: {$str_info}</li>";

			$nodes2 = $xml->department[$j]->book[$i]->publishing_house;
			$count2 = count($nodes2);
			$arr = array();
			for ($k=0; $k < $count2; $k++) { 
				array_push($arr, $xml->department[$j]->book[$i]->publishing_house[$k]['name']);
			}
			$str_info = implode(', ', $arr);
			echo "<li>Издательство: {$str_info}</li>";
			echo "</ul>";
			echo "</ul>";
		}
		$nodes1 = $xml->department[$j]->employee;
		$count1 = count($nodes1);
		for ($i=0; $i < $count1; $i++) { 
			echo "<ul>";
			echo "<li>Сотрудник: {$nodes1[$i]['full_name']} - {$xml->department[$j]->employee[$i]['job_title']}</li>";
			echo "</ul>";
		}
	}
?>
<title>Simple</title>

<style>
	form{
		display: flex;
		position: fixed;
		top: 20px;
		right: 20px;
		width: 20%;
		height: 20%;
		flex-flow: wrap;
	}
	input{
		width: 100%;
		margin-bottom: 10px;

	}

</style>
<form action="edit.php" method="POST">
	<input type="submit" name="buttonEDIT" value="Редактировать">
	<input type="submit" name="buttonADD" value="Добавить">
	<input type="submit" name="buttonDELETE" value="Удалить">
</form>