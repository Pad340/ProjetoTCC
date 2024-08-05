<?php

require "vendor/autoload.php";

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
redirect($uri . "/projetotcc/themes/web/");
?>
Algo de errado aconteceu.