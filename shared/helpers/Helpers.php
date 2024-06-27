<?php

class Helpers
{
    function redirect($url)
    {
        header("Location: $url");
        exit();
    }

}