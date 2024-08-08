<?php

/*
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

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
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
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

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($formats, 'ISO-8859-1', 'UTF-8'), $replace))
        )
    );
    return $slug;
}

/**
 * Converte uma string em "StudlyCase" (cada palavra começa com uma letra maiúscula, sem espaços ou hífens)
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * Converte uma string em "camelCase" (sem espaços, com a primeira letra minúscula e cada palavra subsequente
 * começando com uma letra maiúscula)
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
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
 * Formata texto de uma área de texto HTML para ser exibido como parágrafos HTML
 * @param string $text
 * @return string
 */
function str_textarea(string $text): string
{
    $text = filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS);
    $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;"];
    return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
}

/**
 * Limita uma string a um número máximo de palavras, adicionando um ponteiro (como "...") no final se a string
 * for truncada
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * Limita uma string a um número máximo de caracteres, adicionando um ponteiro (como "...") no final se a string
 * for truncada
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * Formata um valor numérico como um preço em formato brasileiro (com vírgula para decimais e ponto para milhares)
 * @param string|null $price Se for null, define como 0
 * @return string
 */
function str_price(?string $price): string
{
    return number_format((!empty($price) ? $price : 0), 2, ",", ".");
}

/**
 * Sanitiza uma string de busca, removendo caracteres especiais, e retorna "all" se a string de busca estiver vazia
 * @param string|null $search
 * @return string
 */
function str_search(?string $search): string
{
    if (!$search) {
        return "all";
    }

    $search = preg_replace("/[^a-z0-9A-Z\@\ ]/", "", $search);
    return (!empty($search) ? $search : "all");
}

/*
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * Constrói uma URL completa baseada no ambiente de desenvolvimento ou produção e no caminho fornecido
 * @param string|null $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * Retorna a URL de referência anterior (a página de onde o usuário veio) ou a URL base se a referência não estiver disponível
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * Retorna o URL atual.
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
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * ################
 * ###   DATE   ###
 * ################
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
    return (new DateTime($date))->format(CONF_DATE_FORMAT_BR);
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
    return (new DateTime($date))->format(CONF_DATE_FORMAT_APP);
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
 * ####################
 * ###   PASSWORD   ###
 * ####################
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

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
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
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

