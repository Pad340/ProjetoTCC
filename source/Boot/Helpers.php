<?php

use JetBrains\PhpStorm\NoReturn;

/*
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * Valida se existe algum carácter especial ou número no nome.
 * @param string $name
 * @return bool TRUE se for valido
 */
function is_name(string $name): bool
{
    if (
        is_numeric(filter_var(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS), FILTER_SANITIZE_NUMBER_INT))
        or preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $name)
    ) {
        return false;
    }
    return true;
}

/**
 * Valida o CPF de acordo com o algoritmo oficial utilizado pela Receita Federal do Brasil
 * @param string $cpf
 * @return bool TRUE se for valido
 */
function is_cpf(string $cpf): bool
{
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/i', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/**
 * Retira os carácteres especiais para validar o tamanho da string
 * @param string $phone
 * @return bool
 */
function is_phone_number(string $phone): bool
{
    $phone = preg_replace('/\D/', '', $phone);

    if (strlen($phone) == 10 || strlen($phone) == 11) {
        return true;
    } else {
        return false;
    }
}

/**
 * Valida se o email é valido
 * @param string $email
 * @return bool FALSE se não for valido
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Verifica se a hash é valida e o tamanho da senha
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= PASSWD_MIN_LEN && mb_strlen($password) <= PASSWD_MAX_LEN)) {
        return true;
    }

    return false;
}

/*
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * Converte uma string em um "slug" amigável para URLs, substituindo caracteres especiais por seus equivalentes
 * sem acentuação e convertendo espaços em hífens
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_SPECIAL_CHARS);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(
        ["-----", "----", "---", "--"],
        "-",
        str_replace(
            " ",
            "-",
            trim(strtr(
                mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($formats, 'ISO-8859-1', 'UTF-8'),
                $replace
            ))
        )
    );
    return $slug;
}

/**
 * Converte uma string para "Title Case" (cada palavra começa com uma letra maiúscula)
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * Deixa apenas a primeira letra da string maiúscula
 * @param string $string
 * @return string
 */
function str_first_letter_to_uppercase(string $string): string
{
    return ucfirst($string);
}

/**
 * Retira os carácteres especiais do CPF, mantendo apenas os números
 * @param string $cpf
 * @return string
 */
function cpf_format(string $cpf): string
{
    return str_replace(['.', '-'], '', $cpf);
}

/**
 * Converte o telefone celular ou fixo para um formato (00)00000000 ou (00)000000000
 * @param string $phone
 * @return string
 */
function phone_number_format(string $phone): string
{
    return str_replace([' ', '-'], '', $phone);
}

/**
 * Formata um valor numérico como um preço em formato brasileiro (com vírgula para decimais e ponto para milhares)
 * @param string $price
 * @return string
 */
function brl_price_format(string $price): string
{
    return number_format($price, 2, ",", ".");
}

/**
 * Converte um preço que está em BRL para o formato decimal do banco de dados
 * @param string $price
 * @return string
 */
function brl_to_decimal(string $price): string {
    return str_replace(',', '.', str_replace('.', '', $price));
}

/*
 * #########
 * ## URL ##
 * #########
 */

/**
 * Retorna o url do sistema.
 * @param string $page
 * @return string
 */
function url(string $page): string
{
    return "/projetotcc/themes/$page";
}

/**
 * Retorna o URL atual
 * @return string
 */
function url_actual(): string
{
    $url = explode('?', $_SERVER['REQUEST_URI']);
    return 'https://' . $_SERVER['HTTP_HOST'] . $url[0];
}

/**
 * Redireciona o navegador para a URL fornecida
 * @param string $url
 */
#[NoReturn] function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    header("Location: {$url}");
    exit;
}

/**
 * Faz um recarregamento na página
 * @param int $time Em segundos
 * @return void
 */
function refresh(int $time = 0): void
{
    echo "<meta http-equiv='refresh' content='$time'>";
}

/**
 * ##########
 * ## DATE ##
 * ##########
 */

/**
 * Formata uma data para um formato especificado
 * @param string|null $date
 * @param string $format
 * @return string Data atual se $date for null
 * @throws Exception
 */
function date_fmt(?string $date, string $format = "d/m/Y H\hi"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}

/**
 * Formata uma data para o formato brasileiro d/m/Y H:i:s
 * @param string|null $date
 * @return string Data atual se $date for null
 * @throws Exception
 */
function date_fmt_br(string $date = null): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(DATE_FORMAT_BR);
}

/**
 * Formata uma data para o formato Y-m-d H:i:s
 * @param string|null $date
 * @return string Data atual se $date for null
 * @throws Exception
 */
function date_fmt_app(string $date = null): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(DATE_FORMAT_APP);
}

/**
 * Converte uma data no formato brasileiro (dd/mm/yyyy) para o formato ISO (yyyy-mm-dd)
 * @param string $date
 * @return string
 */
function date_fmt_back(string $date): string
{
    if (strpos($date, " ")) {
        $date = explode(" ", $date);
        return implode("-", array_reverse(explode("/", $date[0]))) . " " . $date[1];
    }

    return implode("-", array_reverse(explode("/", $date)));
}

/*
 * ##############
 * ## PASSWORD ##
 * ##############
 */

/**
 * Hash de uma senha
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, PASSWD_ALGO, PASSWD_OPTION);
}

/**
 * Verifica se a senha fornecida corresponde ao hash armazenado
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * Verifica se o hash precisa ser re-hash
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, PASSWD_ALGO, PASSWD_OPTION);
}
