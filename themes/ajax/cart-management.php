<?php

// Pega o conteúdo do corpo da requisição JSON
$json_data = file_get_contents('php://input');

// Decodifica o JSON
$data = json_decode($json_data, true);

// Exibe o conteúdo decodificado
var_dump($data);