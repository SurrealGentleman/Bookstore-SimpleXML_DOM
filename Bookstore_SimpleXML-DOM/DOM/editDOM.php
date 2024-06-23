<?
	session_start();
	$dom = new DOMDocument();
	$dom->load('../xml/index.xml') or die('error');
	$arrMAIN = array();
	foreach ($dom->getElementsByTagName('department') as $department) {
		array_push($arrMAIN, $department->getAttribute('id').": ".$department->getAttribute('name'));
	}
	$book_shop = $dom->documentElement;
	if (isset($_POST['buttonADD'])) {
		?><form class="ADD" method="POST">
			<input type="submit" name="buttonADDdepartment" value="Создать новый Отдел">
			<input type="submit" name="buttonADDbook" value="Добавить новую Книгу">
			<input type="submit" name="buttonADDemployee" value="Добавить Сотрудника">
			<input type="submit" name="buttonMAIN" value="На Главную">
		</form><?
	}
	if (isset($_POST['buttonADDdepartment'])) {
		?><form class="ADD" method="POST">
			<label>Название отдела</label>
			<input type="text" name="name_department" required>
			<input type="submit" name="ADDdepartment" value="Добавить">
		</form>
		<form class="ADD" method="POST">
			<input type="submit" name="buttonADD" value="Назад">
		</form><?
	}
	if (isset($_POST['ADDdepartment'])) {
		$name_department = $_POST['name_department'];
		$flag = true;
		foreach ($dom->getElementsByTagName('department') as $department) {
			if ($department->getAttribute('name') == $name_department) {
				$flag = false;
			}
		}
		if ($flag) {
			$arrMAIN = array();
			$countDep = 0;
			foreach ($dom->getElementsByTagName('department') as $department) {
				$countDep+=1;
			}
			$department = $book_shop->appendChild($dom->createElement('department'));
			$department->setAttribute('id', $countDep+=1);
			$department->setAttribute('name', $name_department);
			$dom->save('../xml/index.xml');
			header("Location: indexDOM.php");
		}
		else{
			echo "Ошибка: Такой отдел уже существует";
			?>
			<form class="ADD" method="POST">
				<input type="submit" name="buttonADDdepartment" value="Вернуться">
			</form>
			<?
		}
	}
	if (isset($_POST['buttonADDbook'])) {
		?><form class="ADD" method="POST">
			<label>Название отдела</label>
			<select name="select_Department" required>
				<option></option>
				<? 
					foreach ($arrMAIN as $value) {
						echo "<option>$value</option>";
					}
				?>
			</select>
			<label>Название книги</label>
			<input type="text" name="name_book" required>
			<label>Год издания книги</label>
			<input type="text" name="year_book" required>
			<label>Страниц в книге</label>
			<input type="text" name="pages_book" required>
			<input type="submit" name="ADDbook" value="Далее">
		</form>
		<form class="ADD" method="POST">
			<input type="submit" name="buttonADD" value="Назад">
		</form><?
	}
	if (isset($_POST['ADDbook'])) {
		$select_Department = $_POST['select_Department'];
		$name_book = $_POST['name_book'];
		$year_book = $_POST['year_book']." г.";
		$pages_book = $_POST['pages_book']." стр.";
		$flag = true;
		$pieses = explode(': ', $select_Department);
		$idDEP = $pieses[0];
		$countBook = 0;
		foreach ($dom->getElementsByTagName('department') as $department) {
			foreach ($department->getElementsByTagName('book') as $book) {
				if ($book->getAttribute('name') == $name_book) {
					$flag = false;
				}
			}
		}
		if ($flag) {
			foreach ($dom->getElementsByTagName('department') as $department) {
				foreach ($department->getElementsByTagName('book') as $book) {
					$countBook+=1;
				}
			}
			$_SESSION['countBook'] = $countBook+1;
			foreach ($dom->getElementsByTagName('department') as $department) {
				if ($department->getAttribute('id') == $idDEP) {
					$book = $department->appendChild($dom->createElement('book'));
					$book->setAttribute('id', $countBook+=1);
					$book->setAttribute('name', $name_book);
					$book->setAttribute('year', $year_book);
					$book->setAttribute('pages', $pages_book);
					$dom->save('../xml/index.xml');
					?><form class="ADD" method="POST">
						<label>Сколько жанров добавить?</label><input type="number" name="numGENRE" min="1" max="2" name="text" required>
						<label>Сколько авторов добавить?</label><input type="number" name="numAUTOR" min="1" max="2" name="text" required>
						<label>Сколько расположений добавить?</label><input type="number" name="numLOCATION" min="1" max="2" name="text" required>
						<label>Сколько издательств добавить?</label><input type="number" name="numPUBLISHING_HOUSE" min="1" max="2" name="text" required>
						<input type="submit" name="buttonNEXT" value="Далее">
					</form><?
				}
			}
		}
		else{
			echo "Ошибка: Такая книга уже существует";
			?><form class="ADD" method="POST">
				<input type="submit" name="buttonADDbook" value="Вернуться">
			</form><?
		}
	}
	if (isset($_POST['buttonNEXT'])) {
		$_SESSION['numGENRE'] = $_POST['numGENRE'];
		$_SESSION['numAUTOR'] = $_POST['numAUTOR'];
		$_SESSION['numLOCATION'] = $_POST['numLOCATION'];
		$_SESSION['numPUBLISHING_HOUSE'] = $_POST['numPUBLISHING_HOUSE'];
		?>
		<form class="ADD" method="POST">
			<label name='genre' value='2'>Жанр</label><?
			for ($i=0; $i < $_POST['numGENRE']; $i++) { 
				?><input type="text" name="<?echo "genre{$i}"?>" required><?
			}?>
			<label>Автор</label><?
			for ($i=0; $i < $_POST['numAUTOR']; $i++) { 
				?><input type="text" name="<?echo "autor{$i}"?>" required><?
			}?>
			<label>Расположение</label><?
			for ($i=0; $i < $_POST['numLOCATION']; $i++) { 
				?><input type="text" name="<?echo "location{$i}"?>" required><?
			}?>
			<label>Издательство</label><?
			for ($i=0; $i < $_POST['numPUBLISHING_HOUSE']; $i++) { 
				?><input type="text" name="<?echo "publishing_house{$i}"?>" required><?
			}?>
			<input type="submit" name="ADDbook_children" value="Добавить">
		</form><?
	}
	if (isset($_POST['ADDbook_children'])) {
		for ($i=0; $i < $_SESSION['numGENRE']; $i++) { 
			if (isset($_POST['genre'.$i])) {
				$flag = true;
				foreach ($dom->getElementsByTagName('genre') as $genre) {
					if ($genre->getAttribute('name') == $_POST['genre'.$i]) {
						$id = $genre->getAttribute('id');
						$flag = false;
					}
				}
			}
			if ($flag) {
				$arr = array();
				foreach ($dom->getElementsByTagName('genre') as $genre) {
					array_push($arr, $genre->getAttribute('id'));
				}
				$arr = array_unique($arr);
				$id = max($arr)+1;
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$genre = $book->appendChild($dom->createElement('genre'));
							$genre->setAttribute('id', $id);
							$genre->setAttribute('name', $_POST['genre'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
			else{
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$genre = $book->appendChild($dom->createElement('genre'));
							$genre->setAttribute('id', $id);
							$genre->setAttribute('name', $_POST['genre'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
		}
		for ($i=0; $i < $_SESSION['numAUTOR']; $i++) { 
			if (isset($_POST['autor'.$i])) {
				$flag = true;
				foreach ($dom->getElementsByTagName('autor') as $autor) {
					if ($autor->getAttribute('full_name') == $_POST['autor'.$i]) {
						$id = $autor->getAttribute('id');
						$flag = false;
					}
				}
			}
			if ($flag) {
				$arr = array();
				foreach ($dom->getElementsByTagName('autor') as $autor) {
					array_push($arr, $autor->getAttribute('id'));
				}
				$arr = array_unique($arr);
				$id = max($arr)+1;
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$autor = $book->appendChild($dom->createElement('autor'));
							$autor->setAttribute('id', $id);
							$autor->setAttribute('full_name', $_POST['autor'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
			else{
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$autor = $book->appendChild($dom->createElement('autor'));
							$autor->setAttribute('id', $id);
							$autor->setAttribute('full_name', $_POST['autor'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
		}
		for ($i=0; $i < $_SESSION['numLOCATION']; $i++) { 
			if (isset($_POST['location'.$i])) {
				$flag = true;
				foreach ($dom->getElementsByTagName('location') as $location) {
					if ($location->getAttribute('name') == $_POST['location'.$i]) {
						$id = $location->getAttribute('id');
						$flag = false;
					}
				}
			}
			if ($flag) {
				$arr = array();
				foreach ($dom->getElementsByTagName('location') as $location) {
					array_push($arr, $location->getAttribute('id'));
				}
				$arr = array_unique($arr);
				$id = max($arr)+1;
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$location = $book->appendChild($dom->createElement('location'));
							$location->setAttribute('id', $id);
							$location->setAttribute('name', $_POST['location'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
			else{
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$location = $book->appendChild($dom->createElement('location'));
							$location->setAttribute('id', $id);
							$location->setAttribute('name', $_POST['location'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
		}
		for ($i=0; $i < $_SESSION['numPUBLISHING_HOUSE']; $i++) { 
			if (isset($_POST['publishing_house'.$i])) {
				$flag = true;
				foreach ($dom->getElementsByTagName('publishing_house') as $publishing_house) {
					if ($publishing_house->getAttribute('name') == $_POST['publishing_house'.$i]) {
						$id = $publishing_house->getAttribute('id');
						$flag = false;
					}
				}
			}
			if ($flag) {
				$arr = array();
				foreach ($dom->getElementsByTagName('publishing_house') as $publishing_house) {
					array_push($arr, $publishing_house->getAttribute('id'));
				}
				$arr = array_unique($arr);
				$id = max($arr)+1;
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$publishing_house = $book->appendChild($dom->createElement('publishing_house'));
							$publishing_house->setAttribute('id', $id);
							$publishing_house->setAttribute('name', $_POST['publishing_house'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
			else{
				foreach ($dom->getElementsByTagName('department') as $department) {
					foreach ($department->getElementsByTagName('book') as $book) {
						if ($book->getAttribute('id') == $_SESSION['countBook']) {
							$publishing_house = $book->appendChild($dom->createElement('publishing_house'));
							$publishing_house->setAttribute('id', $id);
							$publishing_house->setAttribute('name', $_POST['publishing_house'.$i]);
							$dom->save('../xml/index.xml');
							header("Location: indexDOM.php");
						}
					}
				}
			}
		}
	}
	if (isset($_POST['buttonADDemployee'])) {
		?><form class="ADD" method="POST">
			<label>Название отдела</label>
			<select name="select_Department" required>
				<option></option>
				<? 
					foreach ($arrMAIN as $value) {
						echo "<option>$value</option>";
					}
				?>
			</select>
			<label>Имя сотрудника</label>
			<input type="text" name="name_employee" required>
			<label>Должность сотрудника</label>
			<input type="text" name="job_title_employee" required>
			<input type="submit" name="ADDemployee" value="Добавить">
		</form>
		<form class="ADD" method="POST">
			<input type="submit" name="buttonADD" value="Назад">
		</form><?
	}
	if (isset($_POST['ADDemployee'])) {
		$select_Department = $_POST['select_Department'];
		$name_employee = $_POST['name_employee'];
		$job_title_employee = $_POST['job_title_employee'];
		$flag = true;
		$pieses = explode(': ', $select_Department);
		$idDEP = $pieses[0];
		foreach ($dom->getElementsByTagName('employee') as $employee) {
			if ($employee->getAttribute('full_name') == $name_employee && $employee->getAttribute('job_title') == $job_title_employee) {
				$id = $employee->getAttribute('id');
				$flag = false;
			}
		}
		if ($flag) {
			$arr = array();
			foreach ($dom->getElementsByTagName('employee') as $employee) {
				array_push($arr, $employee->getAttribute('id'));
			}
			$arr = array_unique($arr);
			$id = max($arr)+1;
			foreach ($dom->getElementsByTagName('department') as $department) {
				if ($department->getAttribute('id') == $idDEP) {
					$employee = $department->appendChild($dom->createElement('employee'));
					$employee->setAttribute('id', $id);
					$employee->setAttribute('full_name', $name_employee);
					$employee->setAttribute('job_title', $job_title_employee);
					$dom->save('../xml/index.xml');
					header("Location: indexDOM.php");
				}
			}
		}
		else{
			foreach ($dom->getElementsByTagName('department') as $department) {
				if ($department->getAttribute('id') == $idDEP) {
					$employee = $department->appendChild($dom->createElement('employee'));
					$employee->setAttribute('id', $id);
					$employee->setAttribute('full_name', $name_employee);
					$employee->setAttribute('job_title', $job_title_employee);
					$dom->save('../xml/index.xml');
					header("Location: indexDOM.php");
				}
			}
		}
	}
	if (isset($_POST['buttonEDIT'])) {?>
		<form class="ADD" method="POST">
			<label>Название отдела</label>
			<select name="select_Department" required>
				<option></option>
				<? 
					foreach ($arrMAIN as $value) {
						echo "<option>$value</option>";
					}
				?>
			</select>
			<label>Новое название отдела</label>
			<input type="text" name="name_department" required>
			<input type="submit" name="edit" value="Редактировать">
		</form>
		<form class="ADD" method="POST">
			<input type="submit" name="buttonMAIN" value="На Главную">
		</form><?
	}
	if (isset($_POST['edit'])) {
		$select_Department = $_POST['select_Department'];
		$new_name_department = $_POST['name_department'];
		$pieses = explode(': ', $select_Department);
		$int = $pieses[0];
		foreach ($dom->getElementsByTagName('department') as $department) {
			if ($department->getAttribute('id') == $int) {
				$department->removeAttribute('name');
				$department->setAttribute('name', $new_name_department);
			}
		}
		$dom->save('../xml/index.xml');
		header("Location: indexDOM.php");
	}
	if (isset($_POST['buttonDELETE'])) {
		?><form class="ADD" method="POST">
			<label>Отдел</label>
			<select name="selectDEL" required>
				<option></option><? 
					foreach ($arrMAIN as $value) {
						echo "<option>$value</option>";
					}?>
			</select>
			<input type="submit" name="delete" value="Удалить">
		</form>
		<form class="ADD" method="POST">
			<input type="submit" name="buttonMAIN" value="На Главную">
		</form><?
	}
	if (isset($_POST['delete'])) {
		$str = $_POST['selectDEL'];
		$pieses = explode(': ', $str);
		$int = $pieses[0];
		foreach ($dom->getElementsByTagName('department') as $department) {
			if ($department->getAttribute('id') == $int) {
				$department->parentNode->removeChild($department);
			}
		}
		$dom->save('../xml/index.xml');
		header("Location: indexDOM.php");
	}
	if (isset($_POST['buttonMAIN'])) {
		header('Location: indexDOM.php');
	}
?>
<title>Редактирование DOM</title>
<style>
	.ADD{
		display: flex;
		width: 20%;
		flex-flow: wrap;
	}
	.ADD input{
		width: 100%;
		margin-bottom: 10px;
	}
	select{
		width: 100%;
		margin-bottom: 5px;
	}
</style>