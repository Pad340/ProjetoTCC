<?php

namespace Autoload\Models;

/**
 * Gera alertas em tela
 */
class Alert
{
    private string $message;
    private string $type;

    /**
     * Constructor
     * @param string $message
     * @param string $type
     */
    public function __construct(string $message, string $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Retorna o código HTML para exibir a notificação no topo da página.
     * @param int $time Em segundos
     * @return string
     */
    public function getHtml(int $time = 4): string
    {
        $msTime = $time * 1000;
        return '
        <div id="notification" class="notification ' . $this->type . '">
            <span id="notificationMessage">' . $this->message . '</span>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const notification = document.getElementById("notification");
                let notificationTime = ' . $msTime . ';
                
                // Mostrar a notificação após um pequeno atraso para animação
                setTimeout(function() {
                    notification.classList.add("show");
                }, 100); // 100ms para garantir que o DOM está pronto
    
                // Ocultar a notificação automaticamente
                setTimeout(function() {
                    notification.classList.remove("show");
                }, notificationTime);
            });
        </script>';
    }

    public function getDiv(): string
    {
        return '
        <div id="notification" class="notification ' . $this->type . '">
            <span id="notificationMessage">' . $this->message . '</span>
        </div>';
    }
}