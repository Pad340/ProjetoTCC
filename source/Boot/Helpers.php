<?php

namespace Core\Boot;
class Helpers
{

    /** Redireciona a página
     * @param string $url Local de destino
     * @return void
     */
    function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

}