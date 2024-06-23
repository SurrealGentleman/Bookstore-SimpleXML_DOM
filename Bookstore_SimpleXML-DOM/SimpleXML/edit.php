<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Редактирование Simple</title>
</head>
<style>
	.ADD{
		display: flex;
		width: 20%;
		height: 20%;
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
<body>
	<?
		session_start();
		$xml = simplexml_load_file('../xml/index.xml') or die('Не удалось создать объект');
		if (isset($_POST['buttonEDIT'])) {?>
			<form class="ADD" method="POST">
				<label>Название отдела</label>
				<?
					$count = count($xml->children());
					$arrayMAIN = array();
					for ($i=0; $i < $count; $i++) { 
						$array_interim = array();
						foreach ($xml->children()[$i]->attributes() as $value) {
							array_push($array_interim, $value);
						}
						array_push($arrayMAIN, implode(': ', $array_interim));
					}
				?>
				<select name="select_Department" required>
					<option></option>
					<? 
						foreach ($arrayMAIN as $value) {
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
			foreach ($xml->xpath("/book_shop/department[@id={$int}]") as $department) {
				$department['name'] = $new_name_department;
			}
			$xml->asXml('../xml/index.xml');
			header("Location: index.php");
		}

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
			$count = count($xml->children());
			$flag = true;
			for ($i=0; $i < $count; $i++) { 
				if ($xml->children()[$i]->attributes()['name'] == $name_department) {
					$flag = false;
				}
			}
			if ($flag) {
				$node = $xml->addChild('department', '');
				$node->addAttribute('id', $count+1);
				$node->addAttribute('name', $_POST['name_department']);
				$xml->asXml('../xml/index.xml');
				header("Location: index.php");
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
				<?
					$count = count($xml->children());
					$arrayMAIN = array();
					for ($i=0; $i < $count; $i++) { 
						$array_interim = array();
						foreach ($xml->children()[$i]->attributes() as $value) {
							array_push($array_interim, $value);
						}
						array_push($arrayMAIN, implode(': ', $array_interim));
					}
				?>
				<select name="select_Department" required>
					<option></option>
					<? 
						foreach ($arrayMAIN as $value) {
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
			$count = count($xml->children());
			$flag = true;
			$pieses = explode(': ', $select_Department);
			$idDEP = $pieses[0];
			$_SESSION['idDEP'] = $pieses[0];
			for ($i=0; $i < $count; $i++) { 
				foreach ($xml->children()[$i]->book as $value) {
					if ($value->attributes()['name'] == $name_book && $value->attributes()['year'] == $year_book && $value->attributes()['pages'] == $pages_book) {
						$flag = false;
					}
				}
			}
			if ($flag) {
				$arrayMAIN = array();
				for ($i=0; $i < $count; $i++) { 
					foreach ($xml->children()[$i]->book as $value) {
						array_push($arrayMAIN, $value->attributes()['id']);
					}
				}
				$arrayMAIN = array_unique($arrayMAIN);
				$count1 = count($arrayMAIN)+1;
				$node = $xml->children()[$idDEP-1]->addChild('book');
				$node->addAttribute('id', $count1);
				$node->addAttribute('name', $name_book);
				$node->addAttribute('year', $year_book);
				$node->addAttribute('pages', $pages_book);
				$xml->asXml('../xml/index.xml');
				$_SESSION['count1'] = count($arrayMAIN);
				?><form class="ADD" method="POST">
					<label>Сколько жанров добавить?</label><input type="number" name="numGENRE" min="1" max="2" name="text" required>
					<label>Сколько авторов добавить?</label><input type="number" name="numAUTOR" min="1" max="2" name="text" required>
					<label>Сколько расположений добавить?</label><input type="number" name="numLOCATION" min="1" max="2" name="text" required>
					<label>Сколько издательств добавить?</label><input type="number" name="numPUBLISHING_HOUSE" min="1" max="2" name="text" required>
					<input type="submit" name="buttonNEXT" value="Далее">
				</form><?
			}
			else{
				echo "Ошибка: Такая книга уже существует";
				?><form method="POST">
					<input type="submit" name="buttonADDbook" value="Вернуться">
				</form><?
			}
		}
		if (isset($_POST['buttonNEXT'])) {?>
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
			for ($i=0; $i < 2; $i++) { 
				if (isset($_POST['genre'.$i])) {
					$count = count($xml->children());
					$flag = true;
					for ($j=0; $j < $count; $j++) { 
						foreach ($xml->children()[$j]->book as $value) {
							foreach ($value->genre as $value1) {
								if ($value1->attributes()['name'] == $_POST['genre'.$i]) {
									$id = $value1->attributes()['id'];
									$flag = false;
								}
							}
						}
					}
					if ($flag) {
						$arrayMAIN = array();
						for ($j=0; $j < $count; $j++) { 
							foreach ($xml->children()[$j]->book as $value) {
								foreach ($value->genre as $value1) {
									array_push($arrayMAIN, $value1->attributes()['id']);
								}
							}
						}
						$arrayMAIN = array_unique($arrayMAIN);
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->department[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('genre', '');
						$node->addAttribute('id', count($arrayMAIN)+1);
						$node->addAttribute('name', $_POST['genre'.$i]);
						$xml->asXml('../xml/index.xml');
					}
					else{
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->department[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('genre', '');
						$node->addAttribute('id', $id);
						$node->addAttribute('name', $_POST['genre'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
				}
			}
			for ($i=0; $i < 2; $i++) { 
				if (isset($_POST['autor'.$i])) {
					$count = count($xml->children());
					$flag = true;
					for ($j=0; $j < $count; $j++) { 
						foreach ($xml->children()[$j]->book as $value) {
							foreach ($value->autor as $value1) {
								if ($value1->attributes()['full_name'] == $_POST['autor'.$i]) {
									$id = $value1->attributes()['id'];
									$flag = false;
								}
							}
						}
					}
					if ($flag) {
						$arrayMAIN = array();
						for ($j=0; $j < $count; $j++) { 
							foreach ($xml->children()[$j]->book as $value) {
								foreach ($value->autor as $value1) {
									array_push($arrayMAIN, $value1->attributes()['id']);
								}
							}
						}
						$arrayMAIN = array_unique($arrayMAIN);
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('autor', '');
						$node->addAttribute('id', count($arrayMAIN)+1);
						$node->addAttribute('full_name', $_POST['autor'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
					else{
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('autor', '');
						$node->addAttribute('id', $id);
						$node->addAttribute('full_name', $_POST['autor'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
				}
			}
			for ($i=0; $i < 2; $i++) { 
				if (isset($_POST['location'.$i])) {
					$count = count($xml->children());
					$flag = true;
					for ($j=0; $j < $count; $j++) { 
						foreach ($xml->children()[$j]->book as $value) {
							foreach ($value->location as $value1) {
								if ($value1->attributes()['name'] == $_POST['location'.$i]) {
									$id = $value1->attributes()['id'];
									$flag = false;
								}
							}
						}
					}
					if ($flag) {
						$arrayMAIN = array();
						for ($j=0; $j < $count; $j++) { 
							foreach ($xml->children()[$j]->book as $value) {
								foreach ($value->location as $value1) {
									array_push($arrayMAIN, $value1->attributes()['id']);
								}
							}
						}
						$arrayMAIN = array_unique($arrayMAIN);
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('location', '');
						$node->addAttribute('id', count($arrayMAIN)+1);
						$node->addAttribute('name', $_POST['location'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
					else{
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('location', '');
						$node->addAttribute('id', $id);
						$node->addAttribute('name', $_POST['location'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
				}
			}
			for ($i=0; $i < 2; $i++) { 
				if (isset($_POST['publishing_house'.$i])) {
					$count = count($xml->children());
					$flag = true;
					for ($j=0; $j < $count; $j++) { 
						foreach ($xml->children()[$j]->book as $value) {
							foreach ($value->publishing_house as $value1) {
								if ($value1->attributes()['name'] == $_POST['publishing_house'.$i]) {
									$id = $value1->attributes()['id'];
									$flag = false;
								}
							}
						}
					}
					if ($flag) {
						$arrayMAIN = array();
						for ($j=0; $j < $count; $j++) { 
							foreach ($xml->children()[$j]->book as $value) {
								foreach ($value->publishing_house as $value1) {
									array_push($arrayMAIN, $value1->attributes()['id']);
								}
							}
						}
						$arrayMAIN = array_unique($arrayMAIN);
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('publishing_house', '');
						$node->addAttribute('id', count($arrayMAIN)+1);
						$node->addAttribute('name', $_POST['publishing_house'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
					else{
						$count2 = count($xml->department[$_SESSION['idDEP']-1]->book);
						$node = $xml->children()[$_SESSION['idDEP']-1]->book[$count2-1]->addChild('publishing_house', '');
						$node->addAttribute('id', $id);
						$node->addAttribute('name', $_POST['publishing_house'.$i]);
						$xml->asXml('../xml/index.xml');
						header("Location: index.php");
					}
				}
			}
		}
		if (isset($_POST['buttonADDemployee'])) {
			?><form class="ADD" method="POST">
				<label>Название отдела</label>
				<?
					$count = count($xml->children());
					$arrayMAIN = array();
					for ($i=0; $i < $count; $i++) { 
						$count1 = count($xml->children()[$i]->employee);
						$array_interim = array();
						foreach ($xml->children()[$i]->attributes() as $value) {
							array_push($array_interim, $value);
						}
						array_push($arrayMAIN, implode(': ', $array_interim));
					}
				?>
				<select name="select_Department" required>
					<option></option>
					<? 
						foreach ($arrayMAIN as $value) {
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
			$count = count($xml->children());
			$flag = false;
			$pieses = explode(': ', $select_Department);
			$idDEP = $pieses[0];
			for ($i=0; $i < $count; $i++) { 
				foreach ($xml->children()[$i]->employee as $value) {
					if ($value->attributes()['full_name'] == $name_employee && $value->attributes()['job_title'] == $job_title_employee) {
						$id = $value->attributes()['id'];
						$flag = true;
					}
				}
			}
			if ($flag) {
				$node = $xml->children()[$idDEP-1]->addChild('employee', '');
				$node->addAttribute('id', $id);
				$node->addAttribute('full_name', $name_employee);
				$node->addAttribute('job_title', $job_title_employee);
				$xml->asXml('../xml/index.xml');
				header("Location: index.php");
			}
			else{
				$arrayMAIN = array();
				for ($i=0; $i < $count; $i++) { 
					foreach ($xml->children()[$i]->employee as $value) {
						array_push($arrayMAIN, $value->attributes()['id']);
					}
				}
				$arrayMAIN = array_unique($arrayMAIN);
				$node = $xml->children()[$idDEP-1]->addChild('employee', '');
				$node->addAttribute('id', count($arrayMAIN)+1);
				$node->addAttribute('full_name', $name_employee);
				$node->addAttribute('job_title', $job_title_employee);
				$xml->asXml('../xml/index.xml');
				header("Location: index.php");
			}
		}

		if (isset($_POST['buttonDELETE'])) {
			$nodes = $xml->children();
			$count = count($nodes);
			$arrDEP1 = array();
			for ($i=0; $i < $count; $i++) { 
				$arrDEP = array();
				foreach ($nodes[$i]->attributes() as $value) {
					array_push($arrDEP, $value);
				}
				array_push($arrDEP1, implode(': ', $arrDEP));
			}
			?>
			<form class="ADD" method="POST">
				<label>Отдел</label>
				<select name="selectDEL" required>
					<option></option><? 
				foreach ($arrDEP1 as $value) {
					echo "<option>$value</option>";
				}?>
				</select>
				<input type="submit" name="delete" value="Удалить">
			</form>
			<form class="ADD" method="POST">
				<input type="submit" name="buttonMAIN" value="На Главную">
			</form>
			<?
		}
		if (isset($_POST['delete'])) {
			$str = $_POST['selectDEL'];
			$pieses = explode(': ', $str);
			$int = $pieses[0];
			foreach ($xml->xpath("/book_shop/department[@id={$int}]") as $department) {
				unset($department[0]);
			}
			$xml->asXml('../xml/index.xml');
			header("Location: index.php");
		}


		if (isset($_POST['buttonMAIN'])) {
			header('Location: index.php');
		}
	?>
</body>
</html>