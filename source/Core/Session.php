<?php

namespace Autoload\Core;

/**
 * Gerencia a sessão
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function __get($name)
    {
        if (!empty($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Obtém toda a sessão em forma de objeto
     * @return null|object
     */
    public function all(): ?object
    {
        return (object)$_SESSION;
    }

    /**
     * Cria na sessão o índice $key com o valor $value
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public function set(string $key, mixed $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object)$value : $value);
        return $this;
    }

    /**
     * Remove o índice $key da sessão
     * @param string $key
     * @return Session
     */
    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Verifica se o índice $key existe na sessão
     * @param string $key
     * @return bool TRUE se existir
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Regenera a sessão e apaga a antiga
     * @return Session
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    /**
     * Destrói a sessão
     * @return Session
     */
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }
}