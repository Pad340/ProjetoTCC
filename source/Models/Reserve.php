<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\DB\Update;
use Autoload\Core\Session;
use Exception;

class Reserve
{
    private int $orderType;
    private object $session;
    private object $cart;
    private string $message = '';
    private string $messageType = '';
    const Table = 'Reserve';

    /**
     * Reserve constructor
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->cart = (new Cart())->getCart();
    }

    /**
     * @param int $orderType
     * @return void
     */
    public function generateReserve(int $orderType): void
    {
        $this->orderType = $orderType;

        try {
            $orderID = $this->generateOrder();

            $this->generateLog($orderID);

            (new Cart())->empty();

            $this->message = ($this->orderType == 0 ? 'Reserva' : 'Compra') . ' gerada!';
            $this->messageType = ALERT_SUCCESS;

        } catch (Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = ALERT_ERROR;
        }
    }

    /**
     * Retorna uma notificação
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
     * @return array
     */
    private function prepareOrder(): array
    {
        return [
            'user_id' => $this->session->authUser,
            'reserved_at' => DATE_APP,
            'total_value' => $this->cart->total,
            'redeemed' => $this->orderType
        ];
    }

    /**
     * @return int
     * @throws Exception
     */
    private function generateOrder(): int
    {
        $insert = new Insert();

        $order = [
            'user_id' => $this->session->authUser,
            'reserved_at' => DATE_APP,
            'total_value' => $this->cart->total,
            'redeemed' => $this->orderType
        ];

        if ($insert->insert(self::Table, $order)) {
            return $insert->getLastInsertId();
        }

        throw new Exception('Erro ao gerar ' . ($this->orderType == 0 ? 'reserva' : 'compra') . '!');
    }

    /**
     * @param int $orderID
     * @return void
     * @throws Exception
     */
    private function generateLog(int $orderID): void
    {
        $search = new Select();
        $update = new Update();

        $order = null;
        foreach ($this->cart->products as $product) {
            $quantity = $search->selectFirst(
                'product',
                'WHERE product_id = :id',
                "id={$product['id']}",
                'qtt_stock'
            )['qtt_stock'];

            $newQuantity = $quantity - $product['quantity'];

            if ($newQuantity < 0) {
                throw new Exception('O produto ' . $product['name'] . ' está fora de estoque.');
            }

            $update->update('product', ['qtt_stock' => $newQuantity], 'product_id = :id', ['id' => $product['id']]);

            $order[] = [
                'product_id' => $product['id'],
                'reserve_id' => $orderID,
                'quantity' => $product['quantity']
            ];
        }

        $insert = new Insert();

        foreach ($order as $item) {
            if (!$insert->insert('product_reserve', $item)) {
                throw new Exception('Erro ao registrar!');
            }
        }
    }

}