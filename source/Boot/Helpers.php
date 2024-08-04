<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Redireciona para outra página
 * @param string $url Local de destino
 * @return void
 */
#[NoReturn] function redirect(string $url): void
{
    header("Location: $url");
    exit();
}

/**
 * Verifica se todos os caracteres da string são alfabeticos
 * @param string $string
 * @return bool
 */
function alphabeticString(string $string): bool
{
    return ctype_alpha($string);
}
