<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\Session;

class User
{
    private int $user_id;
    private string $name;
    private string $email;
    private string $password;
    private string $confirmPassword;
    private string $message = '';
    const Table = 'user';

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
        $this->message = 'Cadastro realizado com sucesso!';

        return true;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #####################
    ## Private Methods ##
    #####################

    /**
     * Valida os dados do usuário
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array|null
     */
    private function attempt(string $name, string $email, string $password): ?array
    {
        // Name
        if (is_numeric(filter_var(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS), FILTER_SANITIZE_NUMBER_INT))
            || preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $name)) {
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
        $search->selectFirst(self::Table, 'WHERE email = :email', "email={$email}");

        if ($search->getRowCount() > 0) {
            $this->message = 'O e-mail informado já está cadastrado.';
            return null;
        }

        // Password
        if (!(mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
            $this->message = 'A senha deve ter entre ' . CONF_PASSWD_MIN_LEN . ' e ' . CONF_PASSWD_MAX_LEN . ' carácteres.';
            return null;
        }

        if (!(is_numeric(filter_var($password, FILTER_SANITIZE_NUMBER_INT))
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $password))) {
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