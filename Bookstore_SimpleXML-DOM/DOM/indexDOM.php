<?
	session_start();
	unset($_SESSION['numGENRE']);
	unset($_SESSION['numAUTOR']);
	unset($_SESSION['numLOCATION']);
	unset($_SESSION['numPUBLISHING_HOUSE']);
	unset($_SESSION['countBook']);
	$dom = new DOMDocument();
	$dom->load('../xml/index.xml') or die('error');
	$book_shop = $dom->getElementsByTagName('book_shop');
	foreach ($book_shop as $title) {
		echo "<h1>{$title->getAttribute('title')}</h1>";
	}
	echo "<ul>";
	foreach ($dom->getElementsByTagName('department') as $department) {
		echo "<li><h2>Отдел: {$department->getAttribute('name')}</h2></li>";
		echo "<ul>";
		foreach ($department->getElementsByTagName('book') as $book) {
			echo "<li>Книга: {$book->getAttribute('name')} - {$book->getAttribute('year')}, {$book->getAttribute('pages')}</li>";
			echo "<ul>";
			$arr = array();
			foreach ($book->getElementsByTagName('genre') as $value) {
				array_push($arr, $value->getAttribute('name'));
			}
			echo "<li>Жанр: ".implode(', ', $arr)."</li>";
			$arr = array();
			foreach ($book->getElementsByTagName('autor') as $value) {
				array_push($arr, $value->getAttribute('full_name'));
			}
			echo "<li>Автор: ".implode(', ', $arr)."</li>";
			$arr = array();
			foreach ($book->getElementsByTagName('location') as $value) {
				array_push($arr, $value->getAttribute('name'));
			}
			echo "<li>Расположение: ".implode(', ', $arr)."</li>";
			$arr = array();
			foreach ($book->getElementsByTagName('publishing_house') as $value) {
				array_push($arr, $value->getAttribute('name'));
			}
			echo "<li>Издательство: ".implode(', ', $arr)."</li>";
			echo "</ul>";
		}
		foreach ($department->getElementsByTagName('employee') as $employee) {
			echo "<li>Сотрудник: {$employee->getAttribute('full_name')} - {$employee->getAttribute('job_title')}</li>";
		}
		echo "</ul>";
	}
	echo "</ul>";
?>
<title>DOM</title>
<style>
	ul{
		list-style-type: none;
	}
	li{
		margin-bottom: 5px;
	}
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
<form action="editDOM.php" method="POST">
	<input type="submit" name="buttonEDIT" value="Редактировать">
	<input type="submit" name="buttonADD" value="Добавить">
	<input type="submit" name="buttonDELETE" value="Удалить">
</form>