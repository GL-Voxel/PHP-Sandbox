<title>Поиск robot.txt</title>
<?php
	function CheckRobotTXT($link){//Проверка на наличие robot.txt в файле
		# vk.com - Возникла ошибка, при получении файла
		# yandex.ru - находит, вычситывает размер robots.txt
		$link = $link.'/robots.txt';       // конкатенация строки с URL со сторокой "robotstxt"
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
	
	if(isset($_GET['link']))
		if(CheckRobotTXT($_GET['link'])){
			$file_headers = @get_headers($link); // подготавливаем headers страницы
			$file = fopen('robots.txt', 'w'); // открываем файл для записи, поехали!
			$ch = curl_init(); // инициализация cURL
			curl_setopt($ch, CURLOPT_URL, $main_str);
			curl_setopt($ch, CURLOPT_FILE, $file);
			curl_exec($ch);
			fclose($file);
			curl_close($ch);				 
			global $resultfile; // описываем как глобальную переменную
			$resultfile = 'robots.txt'; // файл, который получили
			echo $link."\n";
			echo "Директива Host: ";
			echo "Размер файла: ".filesize($resultfile)." байт";
			if(HasHost($resultfile))
				echo "Есть";
			else
				echo "Отсутствует";
			
		}
		else
			echo "Файл отсутствует robot.txt по ссылке: ".$link;
?>

<form action = "RobotSearch.php"  method = "GET" name = "inputLink" id = "inputLink"/>
	Введите ссылку на сайт:<input type = "text" id = "link" required = 1/>
	<input type = "submit" value = "Проверить">
</form>
