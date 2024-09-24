<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\Session;
use JetBrains\PhpStorm\NoReturn;

class User
{
    private string $message = '';
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
            return false;
        }

        (new Session())->set("authUser", $insert->getLastInsertId());
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
        $user = $findUser->selectFirst(self::Table, 'WHERE email = :e', "e={$email}", 'user_id, password, status_account');

        if (!$user) {
            $this->message = 'O e-mail informado não está cadastrado no sistema.';
            return false;
        }

        if ($user['status_account'] != 1) {
            $this->message = 'Usuário inativo, entre em contato com um operador.';
            return false;
        }

        if (!passwd_verify($password, $user['password'])) {
            $this->message = 'E-mail ou senha incorretos.';
            return false;
        }

        (new Session())->set("authUser", $user['user_id']);
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

        if ($session->has("authSeller")) $session->unset('authSeller');

        redirect('../web/');
    }

    /**
     * Mensagem de retorno das operações realizadas
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
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
            return null;
        }
        $name = str_title($name);

        // Email
        if (!is_email($email)) {
            $this->message = 'O e-mail é de formato inválido.';
            return null;
        }

        $search = new Select();
        $mail = $search->selectFirst(self::Table, 'WHERE email = :email', "email={$email}", 'user_id');

        if ($mail) {
            $this->message = 'O e-mail informado já está cadastrado.';
            return null;
        }

        // Password
        if (!(mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
            $this->message = 'A senha deve ter entre ' . CONF_PASSWD_MIN_LEN . ' e ' . CONF_PASSWD_MAX_LEN . ' carácteres.';
            return null;
        }

        if (!(is_numeric(filter_var($password, FILTER_SANITIZE_NUMBER_INT))
            and preg_match('/[A-Z]/', $password)
            and preg_match('/[a-z]/', $password)
            and preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $password))) {
            $this->message = 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um carácter especial.';
            return null;
        }
        $password = passwd($password);

        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => CONF_DATE_APP,
            'updated_at' => CONF_DATE_APP
        ];
    }
}