<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\Session;
use JetBrains\PhpStorm\NoReturn;

/**
 * Gerencia o usuário
 */
class User
{
    private string $message = '';
    private string $messageType = '';
    const Table = 'user';

    /**
     * Realiza o cadastro do usuário no sistema
     * @param string $name
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function register(string $name, string $email, string $password): bool
    {
        $user = $this->attempt($name, $email, $password);
        if (!$user) return false;

        $insert = new Insert();

        if (!$insert->insert(self::Table, $user)) {
            $this->message = 'Ocorreu um erro ao cadastrar os dados.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        (new Session())->set("authUser", $insert->getLastInsertId());
        (new Session())->set("username", $user['name']);
        return true;
    }

    /**
     * Realiza o login do usuário
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        $findUser = new Select();
        $user = $findUser->selectFirst(
            self::Table,
            'WHERE email = :e',
            "e={$email}",
            'user_id, name, password, status_account'
        );

        if (!$user) {
            $this->message = 'O e-mail informado não está cadastrado no sistema.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        if ($user['status_account'] != 1) {
            $this->message = 'Usuário inativo, entre em contato com um operador.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        if (!passwd_verify($password, $user['password'])) {
            $this->message = 'E-mail ou senha incorretos.';
            $this->messageType = ALERT_WARNING;
            return false;
        }

        (new Session())->set("authUser", $user['user_id']);
        (new Session())->set("username", $user['name']);
        return true;
    }

    /**
     * Desloga o usuário do sistema.
     * @return void
     */
    #[NoReturn] public function logout(): void
    {
        $session = new Session();
        $session->unset('authUser');
        $session->unset('username');

        if ($session->has("authSeller")) $session->unset('authSeller');

        redirect('../web/');
    }

    /**
     * Mensagem de retorno das operações realizadas
     * @return string
     */
    public function getMessage(): string
    {
        return (new Alert($this->message, $this->messageType))->getHtml();
    }

    #####################
    ## Private Methods ##
    #####################

    /**
     * Valida os dados do cadastro
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array|null
     */
    private function attempt(string $name, string $email, string $password): ?array
    {
        // Name
        if (!is_name($name)) {
            $this->message = 'O nome não deve conter números e nem carácteres especiais.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $name = str_title($name);

        // Email
        if (!is_email($email)) {
            $this->message = 'O e-mail é de formato inválido.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        $search = new Select();
        $mail = $search->selectFirst(self::Table, 'WHERE email = :email', "email={$email}", 'user_id');

        if ($mail) {
            $this->message = 'O e-mail informado já está cadastrado.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        // Password
        if (!(mb_strlen($password) >= PASSWD_MIN_LEN && mb_strlen($password) <= PASSWD_MAX_LEN)) {
            $this->message = 'A senha deve ter entre ' . PASSWD_MIN_LEN . ' e ' . PASSWD_MAX_LEN . ' carácteres.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        if (!(is_numeric(filter_var($password, FILTER_SANITIZE_NUMBER_INT))
            and preg_match('/[A-Z]/', $password)
            and preg_match('/[a-z]/', $password))
        ) {
            $this->message = 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um carácter especial.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $password = passwd($password);

        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => DATE_APP,
            'updated_at' => DATE_APP
        ];
    }
}