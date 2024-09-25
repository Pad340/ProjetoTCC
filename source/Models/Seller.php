<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\DB\Update;
use Autoload\Core\Session;

class Seller
{
    private string $message = '';
    private string $messageType = '';
    const Table = 'seller';

    /**
     * Faz o cadastro de vendedor de um usuário
     * @param string $name
     * @param string $cpf
     * @param string $phone_number
     * @return bool
     */
    public function register(string $name, string $cpf, string $phone_number): bool
    {
        $seller = $this->attempt($name, $cpf, $phone_number);

        if (!$seller) return false;

        $insert = new Insert();

        if (!$insert->insert(self::Table, $seller)) {
            $this->message = 'Ocorreu um erro ao cadastrar os dados.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        (new Session())->set("authSeller", $insert->getLastInsertId());
        return true;
    }

    /**
     * Verifica se o usuário possui cadastro de vendedor e se este cadastro está ativo
     * @return void
     */
    public function login(): void
    {
        $session = new Session();
        $search = new Select();
        $seller = $search->selectFirst('seller', 'WHERE user_id = :u', "u={$session->authUser}", 'seller_id, status_account');

        if ($seller) {
            if ($seller['status_account'] == 1) {
                (new Session())->set('authSeller', $seller['seller_id']);
            }
        }
    }

    /**
     * Habilita a conta de vendedor
     * @return bool
     */
    public function enable(): bool
    {
        $session = new Session();
        $search = new Select();
        $result = $search->selectFirst(self::Table, 'WHERE user_id = :u', "u={$session->authUser}", 'seller_id');

        if ($result) {
            $update = new Update();
            $disable = $update->update('product', ['status_product' => 1], 'seller_id = :id', [':id' => $result['seller_id']]);
            $disable = $update->update(self::Table, ['status_account' => 1, 'updated_at' => DATE_APP], 'seller_id = :id', [':id' => $result['seller_id']]);

            if ($disable) {
                $session->unset('authSeller');
                $this->message = 'Sua conta de vendedor foi reativada com sucesso.';
                $this->messageType = ALERT_SUCCESS;
                return true;
            }
        }

        $this->message = 'Erro ao desativar sua conta de vendedor. Contate um administrador.';
        $this->messageType = ALERT_ERROR;
        return false;
    }

    /**
     * Desabilita a conta de vendedor do usuário
     * @return bool
     */
    public function disable(): bool
    {
        $session = new Session();
        $update = new Update();
        $disable = $update->update('product', ['status_product' => 0], 'seller_id = :id', [':id' => $session->authSeller]);
        $disable = $update->update(self::Table, ['status_account' => 0, 'updated_at' => DATE_APP], 'seller_id = :id', [':id' => $session->authSeller]);

        if ($disable) {
            $session->unset('authSeller');
            $this->message = 'Sua conta de vendedor foi desativada com sucesso.';
            $this->messageType = ALERT_SUCCESS;
            return true;
        }

        $this->message = 'Erro ao desativar sua conta de vendedor. Contate um administrador.';
        $this->messageType = ALERT_ERROR;
        return false;
    }

    /**
     * Mensagem de retorno ao utilizar alguma função
     * @return string
     */
    public function getMessage(): string
    {
        return (new Alert($this->message, $this->messageType))->getHtml();
    }

    #####################
    ## Private Methods ##
    #####################

    public function attempt(string $name, string $cpf, string $phone_number): ?array
    {
        $session = new Session();

        $search = new Select();
        $user = $search->selectFirst(self::Table, 'WHERE user_id = :u', "u={$session->authUser}");

        if ($user) {
            $this->message = 'Você já possui uma conta de vendedor.';
            $this->messageType = ALERT_ERROR;
            return null;
        }

        // Nome
        if (!is_name($name)) {
            $this->message = 'O nome não deve conter números e nem carácteres especiais.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $name = str_first_letter_to_uppercase($name);

        // CPF
        if (!is_cpf($cpf)) {
            $this->message = 'O CPF informado não é valido.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $cpf = cpf_format($cpf);

        $search->selectFirst(self::Table, 'WHERE cpf = :cpf', "cpf={$cpf}");
        if ($search->getRowCount() > 0) {
            $this->message = 'O CPF informado já está cadastrado.';
            $this->messageType = ALERT_ERROR;
            return null;
        }

        // Número de telefone
        if (!is_phone_number($phone_number)) {
            $this->message = 'O número de telefone informado não é valido.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $phone_number = phone_number_format($phone_number);

        $number = $search->selectFirst(self::Table, 'WHERE phone_number = :pn', "pn={$phone_number}");
        if ($number) {
            $this->message = 'O número de telefone informado já existe no sistema.';
            $this->messageType = ALERT_ERROR;
            return null;
        }

        return [
            'user_id' => $session->authUser,
            'name' => $name,
            'cpf' => $cpf,
            'phone_number' => $phone_number,
            'created_at' => DATE_APP,
            'updated_at' => DATE_APP
        ];
    }

}