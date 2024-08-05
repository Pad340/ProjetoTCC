<?php

namespace Autoload\Models;

use Autoload\Core\DB\DML\Select;

class User
{
    private string $name;
    private string $email;
    private string $password;
    private string $confirmPassword;
    private string $message = '';
    const Entity = 'user';

    public function __construct(string $name, string $email, string $password, string $confirmPassword)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    public function register(): bool
    {
        if (!$this->isValidName()) return false;
        if (!$this->isValidEmail()) return false;
        if (!$this->duplicate()) return false;
        if (!$this->isValidPassword()) return false;



        return true;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #####################
    ## Private Methods ##
    #####################

    private function isValidName(): bool
    {
        if (is_numeric(filter_var(filter_var($this->name, FILTER_SANITIZE_SPECIAL_CHARS), FILTER_SANITIZE_NUMBER_INT))
            || preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $this->name)) {
            $this->message = 'O nome não deve conter números e nem caracteres especiais.';
            return false;
        }
        $this->name = str_title($this->name);
        return true;
    }

    /**
     * Busca um registro no BD com o email enviado para verificar se o email já existe no sistema
     * @return bool true caso não exista uma duplicata
     */
    private function duplicate(): bool
    {
        $search = new Select();
        $search->selectFirst(self::Entity, 'WHERE email = :email', "email={$this->email}");

        if ($search->getRowCount() > 0) {
            $this->message = 'Este e-mail já está em uso.';
            return false;
        }
        return true;
    }

    private function isValidEmail(): bool
    {
        if (!is_email($this->email)) {
            $this->message = 'O e-mail é de formato inválido.';
        }
        return true;
    }

    private function isValidPassword(): bool
    {
        if ($this->password !== $this->confirmPassword) {
            $this->message = 'Confirme sua senha corretamente!';
            return false;
        }

        if (!(mb_strlen($this->password) >= CONF_PASSWD_MIN_LEN && mb_strlen($this->password) <= CONF_PASSWD_MAX_LEN)) {
            $this->message = 'A senha deve ter entre ' . CONF_PASSWD_MIN_LEN . ' e ' . CONF_PASSWD_MAX_LEN . ' caracteres.';
            return false;
        }

        if (!(is_numeric(filter_var($this->password, FILTER_SANITIZE_NUMBER_INT))
            && preg_match('/[A-Z]/', $this->password)
            && preg_match('/[a-z]/', $this->password)
            && preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $this->password))) {
            $this->message = 'A senha deve conter pelo menos uma letra maiuscula, uma minuscula, um número e um caractere especial.';
            return false;
        }
        $this->password = passwd($this->password);

        return true;
    }
}