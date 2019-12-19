<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class ReviewsController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;
    /** @var MyLogin $login sprava prihlaseni. */
    private $login;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.php");
        require_once (DIRECTORY_MODELS ."/MyLogin.class.php");
        $this->db = new DatabaseModel();
        $this->login = new MyLogin();

    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        global $tplData;
        $tplData = [];
        $tplData['title'] = $pageTitle;
        $tplData['reviews'] = $this->db->getAllReviews();
        $tplData['autors'] = $this->db->getAllUsers();
        if($tplData['logged'] = $this->login->isUserLogged()){
            $tplData['user'] = $this->login->getLoggedUserData();
        }

        // zpracovani odeslanych formularu
        if(isset($_POST['action'])){
            if($_POST['action'] == 'logout'){
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
                header('Location: index.php?page=uvod');
            }
            else {
                echo "WARNING: Neznámá akce.";
                header('Location: index.php?page=uvod');
            }
            echo "<br>";
        }


        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS."/ReviewsTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>