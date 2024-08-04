<?php

require "vendor/autoload.php";
use Autoload\Boot\Helpers;

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
(new Helpers())->redirect($uri . "/projetotcc/themes/web/");
?>
Algo de errado aconteceu.