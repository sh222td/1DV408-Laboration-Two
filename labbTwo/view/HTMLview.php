<?php

class HTMLView {

		public function echoHTML($body) {
			
			setlocale(LC_ALL, 'sv_SE');
			$dat = nl2br((strftime('%Aen den %d %B. ' . ('år') . ' %Y. Klockan ' . ('är') . ' %X.')));
			
			echo '<!DOCTYPE html>
				<html>
					<head>
					    <title>Laboration 2 - inloggningsmodul</title>
						<meta charset="UTF-8" />
						<link rel="stylesheet" href="style.css" media="screen">
					</head>
				<body>' .
					$body .
					'<br>' .
					$dat  .
				'</body>
				</html>';
		}
}