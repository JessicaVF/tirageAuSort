<?php

// This class generate the visuals for the administration division and also manage the logic for this section
class AdminForm
{
    
    public function __construct()
    {
        // Starting we create the menu calling the function "addAdminMenu"
        add_action('admin_menu', array($this, 'addAdminMenu'));
    }
    public function addAdminMenu()
    {
        add_menu_page(
        'Tirage Au Sort - votre plugin TirageAuSort',
        'Tirage Au Sort',
        'manage_options',
        'TirageAuSort',
        array($this, 'generateHtml'),
        plugin_dir_url(__FILE__) . '/icon.png'
        );
        add_submenu_page(
            'TirageAuSort',
            'Inscrits',
            'Inscrits',
            'manage_options',
            'TirageAuSort',
            array($this, 'getInfo')
        );
        add_submenu_page(
            'TirageAuSort',
            'Winner',
            'Winner',
            'manage_options',
            'TirageAuSort_winner',
            array($this, 'winner')
        );
    }
// This function display a basic html message to explain the plugin 

    function generateHtml(){
        echo '<h1>' . get_admin_page_title() . '</h1>';
        echo '<p>Bienvenue sur la page d\'accueil du plugin</p>';
        echo '<p> Le shortcode pour utiliser le plugin est: <strong>[form_add_participant][/form_add_participant]</strong> </p>';
        echo  " 
              <p> Si vous voulez choisir le gagnant, vous devez cliquer sur le sous-menu Tirage au sort. Mais, attention : à chaque fois que vous cliquez, un nouveau gagnant sera sélectionné et les informations du précédent seront effacées (vous pouvez toujours les trouver dans la liste des inscrits.</p> ";
    }

// This function recover the list of participants from the Data Base and display them in a html table

    function getInfo(){

        global $wpdb;

        $participants = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tirage_participants");

        $html = '<table class="participants-list" style="border-collapse:collapse"><tbody>';
        $html .= 
        "<tr style='border:2px solid black;'>
            <td width='150' style='border:1px solid black;'>Prenom nom</td>
            <td width='150' style='border:1px solid black;'>Email</td>
            <td width='150' style='border:1px solid black;'>Naissance</td>
            <td width='150' style='border:1px solid black;'>Ville</td>    
        </tr>";
        foreach ($participants as $participant)
        {
            $html .= "<tr>
                    
                    <td width='300' style='border:1px solid black;'>{$participant->prenom} {$participant->nom}</td>
                    <td width='150' style='border:1px solid black;'>{$participant->email}</td>
                    <td width='150' style='border:1px solid black;'>{$participant->dateNaissance}</td>
                    <td width='150' style='border:1px solid black;'>{$participant->ville}</td>
                    ";
            $html .= "</tr>";
        }
        $html .= '<tbody></table>';
        echo $html;
    }

    // This function pick a random winner form the list of participants in the Data Base and display the winner in a html table

    function winner(){

        global $wpdb;

        $winner = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tirage_participants ORDER BY RAND() LIMIT 1");
        
        $html = "<p>Le gagnant du tirage au sort est:</p>";
        
        $html .= 

        "
        <table class='winner' style='border-collapse:collapse'><tbody>

        <tr style='border:2px solid black;'>
            <td width='150' style='border:1px solid black;'>Prenom nom</td>
            <td width='150' style='border:1px solid black;'>Email</td>
            <td width='150' style='border:1px solid black;'>Naissance</td>
            <td width='150' style='border:1px solid black;'>Ville</td>    
        </tr>";

        $html .= 
        
        "<tr>
            <td width='300' style='border:1px solid black;'>{$winner[0]->prenom} {$winner[0]->nom}</td>
            <td width='150' style='border:1px solid black;'>{$winner[0]->email}</td>
            <td width='150' style='border:1px solid black;'>{$winner[0]->dateNaissance}</td>
            <td width='150' style='border:1px solid black;'>{$winner[0]->ville}</td>
        </tr>";
        
        echo $html;
    }
    
}