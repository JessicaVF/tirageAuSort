<?php
class Tirage_Session
{
    public function __construct()
    {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function createMessage($type, $message)
    {
        $_SESSION['tirage-form'] =
            [
                'type'    => $type,
                'message' => $message
            ];
    }

   
    public function getMessage()
    {
        return isset($_SESSION['tirage-form']) && count($_SESSION['tirage-form']) > 0 ? $_SESSION['tirage-form'] : false;
    }


    public function destroy()
    {
        
        $_SESSION['tirage-form'] = array();
        
        
    }
}