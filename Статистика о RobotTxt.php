<title>Поиск robot.txt</title>
<?php
	//vk.com - Возникла ошибка, при получении файла
	//yandex.ru - находит, выcчитывает размер robots.txt
	function CheckRobotTXT($link){//Проверка на наличие robot.txt в файле
		$link = $link.'/robots.txt';       // конкатенация строки с URL со сторокой "robots.txt"
		$file_headers = @get_headers($link); // подготавливаем headers страницы
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {	 
			return False;
		} else if ($file_headers[0] == 'HTTP/1.1 200 OK') {
			return True;
		}
	}
	
	function HasHost($file){//Проверка наличия директивы Host
		if (file_exists($file)){
			$textget = file_get_contents($file); // Начинаем обрабатывать файл, если все прошло успешно
			htmlspecialchars($textget); // при желании, можно вывести на экран через echo
			 if (preg_match("/Host/", $textget))
				return True;
			else
				return False;
		}
	}
	
	if(isset($_GET['link'])){
		$link = $_GET['link'];
		echo $link."\n";
		if(CheckRobotTXT($link)){
			echo $link."\n";
			$file = fopen('robots.txt', 'w'); 
			$ch = curl_init(); // инициализация cURL
			curl_setopt($ch, CURLOPT_URL, $_GET['link']);
			curl_setopt($ch, CURLOPT_FILE, $file);
			curl_exec($ch);
			fclose($file);
			curl_close($ch);				 
			global $resultFile; // описываем как глобальную переменную
			$resultfile = 'robots.txt'; // файл, который получили
			echo "Вес файла: ".filesize($resultFile)." байт";
			echo "Директива Host: ";
			if(HasHost($resultFile))
				echo "Есть";
			else
				echo "Отсутствует";
		}
		else
			echo "Файл отсутствует robot.txt по ссылке";
	}
?>

<form action = "RobotSearch.php"  method = "GET" name = "inputLink" id = "inputLink">
	Введите ссылку на сайт:<input type = "text" id = "link" required = 1>
	<input type = "submit" value = "Проверить">
</form>
