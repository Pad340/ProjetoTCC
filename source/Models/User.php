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
        if ($this->duplicate()) return false;
        //PAREI AQUI
        if ($this->isValidName()) return false;

        //if ($this->isValidEmail()) return false;
        if ($this->isValidPassword()) return false;

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
     * Busca um registro no BD com o email enviado para verificar se o email já existe no sistema
     * @return bool true caso exista uma duplicata
     */
    private function duplicate(): bool
    {
        $search = new Select();
        $search->selectFirst(self::Entity, 'WHERE email = :email', "email={$this->email}");

        if ($search->getRowCount() > 0) {
            $this->message = 'Este e-mail já está cadastrado.';
            return true;
        }
        return false;
    }

    /**
     * Verifica se existe apenas caracteres alfabéticos no nome
     * @return bool
     */
    private function isValidName(): bool
    {
        if (alphabeticString($this->name)) {
            $this->message = 'Nome de usuário inválido.';
            return false;
        }
        return true;
    }

    private function isValidEmail(): bool
    {
        return true;
    }

    private function isValidPassword(): bool
    {
        echo 'to aqui';
        if ($this->password !== $this->confirmPassword) {
            $this->message = 'Confirme sua senha corretamente!';
            return false;
        }

//        $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d].\S{8,36}$/';
//        if (preg_match($pattern, $this->password)) {
//            return false;
//        }

        return true;
    }
}