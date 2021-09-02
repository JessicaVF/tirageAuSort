<?php
/*
Plugin Name: tirage au sort - jessica
Plugin URI: https://www.linkedin.com/in/jessica-villar-fuentes/
Description: Petit plugin à créer pour le examen de Wordpress :)
Version: 0.1
Author: Jessica Villar
Author URI: https://www.linkedin.com/in/jessica-villar-fuentes/
License: GPL2
*/

require_once 'TirageSession.php';

class TirageAuSort{
    
    public function __construct(){

        // Here we create the shortcode, with the command to use for use it and the function that it calls
        add_shortcode('form_add_participant', array($this, 'addParticipant'));

        // The activation hook will create our database (calling the function install)
        // As the unistall hook will drop our database if we deactived the plugin (calling the function uninstall)
        register_activation_hook(__FILE__, array('tirageAuSort', 'install'));
        register_uninstall_hook(__FILE__, array('tirageAuSort', 'uninstall'));

        // This 'action' will call the function "saveInscription" to process the addParticipantForm

        add_action('wp_loaded', array($this, 'saveInscription'), 1);


        // This 'action' will call the function checkMessages to display (if needed) error and success messages
        add_action('wp_loaded', array($this, 'checkMessages'), 2);

        // Note about the actions : if they have a number at the end is for create an "execution order"

       // This action call the function 'loadFile', in this case is for "connect" with the css
        add_action('init', array('tirageAuSort', 'loadFile'));
    }
    
    // The function that it's call by the shorcode. This function display a form

    public function addParticipant()
    {
        $html = "<form action='' name='addParticipantForm' method='POST'>
                    <p>
                        <label for='nom'>Nom:</label>
                        <input type='text' name='nom' id='nom'>
                    </p>
                    <p>
                        <label for='prenom'>prenom:</label>
                        <input type='text' name='prenom' id='prenom'>
                    </p>
                    <p>
                        <label for='email'>email:</label>
                        <input type='text' name='email' id='email'>
                    </p>
                    <p>
                        <label for='dateNaissance'>date naissance:</label>
                        <input type='date' name='dateNaissance' id='dateNaissance'>
                    </p>
                    <p>
                        <label for='ville'>ville:</label>
                        <input type='text' name='ville' id='ville'>
                    </p> 
                                
                    <input type='submit' value='confirmer'>
                </form>";
        
        return $html;
    }

    public static function install(){

        global $wpdb;
        $wpdb->query("
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tirage_participants
        (id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, dateNaissance DATE NOT NULL, ville VARCHAR(255) NOT NULL);
        ");
    }
    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tirage_participants;");
    }

    // This is the function that will be called when a user submit the form 'addParticipantForm'
    
    public function saveInscription()
    {
        $tirageSession = new Tirage_Session();

        if (
                (isset($_POST['nom']) && !empty($_POST['nom']))
                &&
                (isset($_POST['prenom']) && !empty($_POST['prenom']))
                &&
                (isset($_POST['email']) && !empty($_POST['email']))
                &&
                (isset($_POST['dateNaissance']) && !empty($_POST['dateNaissance']))
                &&
                (isset($_POST['ville']) && !empty($_POST['ville']))
            )
        {

            global $wpdb;

            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $dateNaissance = $_POST['dateNaissance'];
            $ville = $_POST['ville'];
            

            $participant = $wpdb->get_row("
                SELECT * FROM {$wpdb->prefix}tirage_participants 
                WHERE email = '$email'
            ");

            if (is_null($participant))
            {
                
                $result = $wpdb->insert("{$wpdb->prefix}tirage_participants", array('nom' => $nom, 'prenom'=>$prenom, 'email'=>$email, 'dateNaissance'=>$dateNaissance, 'ville' => $ville));
                if (!$result)
                {
                    
                    $tirageSession->createMessage("error", "Désolé, veuillez essayer ultérieurement.");
                
                } else {
                        
                    $tirageSession->createMessage("success", "Vous êtes inscrit au tirage.");

                                                
                } 
            }
            else{
                $tirageSession->createMessage("error", "Votre email a ete deja utilise dans autre inscription");
            }    
        }else{
            $tirageSession->createMessage("error", "Rempli le formulaire entier svp");
        }
    }
    public function checkMessages()
    {
        $tirageSession = new Tirage_Session();
        
        $message = $tirageSession->getMessage();

        if ($message) {
            echo ("
                <p class='tirage-info " . $message["type"] . "'>
                    " . $message["message"] . "
                </p>
            ");
        }

        $message = $tirageSession->destroy();

    }

    //This function will "load" the files we need to compliment our code; for example css and JS files
    public static function loadFile()
    {
    
        wp_register_style('TirageAuSort', plugins_url('style.css', __FILE__));
        wp_enqueue_style('TirageAuSort');
        wp_register_script('TirageAuSort', plugins_url('script.js', __FILE__));
        wp_enqueue_script('TirageAuSort');
        
    }
}
// We instance our class so it get to work
new TirageAuSort();


























// class CarForm
// {
//     public function __construct()
//     {
//         

//         new Form_Admin();
//     }







