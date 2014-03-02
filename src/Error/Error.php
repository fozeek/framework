<?php

namespace Fk\Error;

/*
 A FAIRE : mettre l'id du message d'erreur lors de l'instanciation
 Et créer une base de tous les message d'erreur (en Json c'est mieux (si pb avec la BDD))

*/

class Error {

	static public function redirect($url) {
		header("Location:/".$url);
	}

	static public function render($code, $params) {
		header("HTTP/1.0 503 Service Unavailable");
		switch ($code) {
			case 1:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Controller not found";
				$origin = "class";
				break;

			case 2:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Action not found";
				$origin = "function";
				break;

			case 3:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Theme not found";
				$origin = "theme :";
				break;

			case 4:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Number of required parameters for action has not been reached";
				$origin = "function :";
				break;

			case 5:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Database connection failed";
				$origin = "Message :";
				break;
			
			default:
				if(!Kernel::getDebugMode())
					self::redirect(Router::getUrl("error", "http", array(404)));
				$message = "Fatal Error catched !";
				$origin = "Message :";
				break;
		} 

		?>
<!DOCTYPE html>
<html>			
	<head>
		<title><?= $message; ?></title>
	</head>
	<body>
		<div style="font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;padding: 30px;border: 1px solid #E5E5E5;background: #F9F9F9;width: 400px;margin: auto;margin-top: 40px;border-radius: 5px;">
		
		
			<span style="font-size: 1.2em;font-weight: bold;"><?= $message; ?></span>
			<pre style="margin-bottom: 0px;white-space : pre-wrap;"><span style="color: grey;"><?= $origin; ?></span> <?= $params; ?></pre>
		</div>
	</body>
</html>
		<?php die();
	}
}

?>