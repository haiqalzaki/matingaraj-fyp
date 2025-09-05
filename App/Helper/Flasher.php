<?php

namespace App\Helper;

class Flasher 
{
    public static function set(string $type, string $message, string $status = 'success') 
    {
        $_SESSION['flash'][$type] = [
            'message' => $message,
            'status'  => $status
        ];
    }

    public static function flash(string $type) 
    {
        if (!empty($_SESSION['flash'][$type])) 
        {
            $status  = htmlspecialchars($_SESSION['flash'][$type]['status'], ENT_QUOTES, 'UTF-8');
            $message = htmlspecialchars($_SESSION['flash'][$type]['message'], ENT_QUOTES, 'UTF-8');

            echo <<<HTML
                <div class="d-flex justify-content-center">
                    <p class="p-1 bg-$status w-75 border rounded-2">$message</p>
                </div>
            HTML;

            unset($_SESSION['flash'][$type]);
        }
    }
}