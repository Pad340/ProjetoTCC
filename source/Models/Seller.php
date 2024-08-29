<?php

namespace Autoload\Models;

class Seller {

    private string $message = '';
    const Table = 'seller';

    public function seller_register(string $name, string $cpf, string $phone_number): bool
    {
        $user = $this->attempt($name, $cpf, $phone_number);


    }

    #####################
    ## Private Methods ##
    #####################

    public function attempt(string $name, string $cpf, string $phone_number): ?array
    {
        // Name
        if (!is_name($name)) {
            $this->message = 'O nome não deve conter números e nem carácteres especiais.';
            return null;
        }
        $name = str_title($name);

        //Parei aqui
    }

}