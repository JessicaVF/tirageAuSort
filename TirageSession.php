<?php

// In this class we manage the messages using "session"

class Tirage_Session
{
    // First we always make sure that there's a session
    public function __construct()
    {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // This function set 'tirage-form' in the session, and will include the type of the message and the message itself
    // In this plugin we use "error" and "success" as types, but as is just a string is possible to pass whatever word to create a new type

    public function createMessage($type, $message)
    {
        $_SESSION['tirage-form'] =
            [
                'type'    => $type,
                'message' => $message
            ];
    }

    // This function return the message saved in the Session; so it can, for example, being display using "echo"
    public function getMessage()
    {
        return isset($_SESSION['tirage-form']) && count($_SESSION['tirage-form']) > 0 ? $_SESSION['tirage-form'] : false;
    }

    // This function 'clean' the session so it can save a new message, as in this plugin we only display one message at the time
    public function destroy()
    {
        
        $_SESSION['tirage-form'] = array();
        
        
    }
}