<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class ArticleManagementController implements IController {

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
            else if($_POST['action'] == 'delete' && isset($_POST['id_clanek']))
            {
                $ok = $this->db->deleteFromTable(TABLE_CLANEK, "id_clanek = ".intval($_POST['id_clanek']));
                if($ok){
                    $tplData['delete'] = "OK: Článek s ID:$_POST[id_clanek] byl smazán z databáze.";
                } else {
                    $tplData['delete'] = "CHYBA: Článek s ID:$_POST[id_clanek] se nepodařilo smazat z databáze.";
                }
            }
            else {
                echo "WARNING: Neznámá akce.";
                header('Location: index.php?page=uvod');
            }
            echo "<br>";
        }

        //// nactu aktulani data clanku
        $tplData['articles'] = $this->db->getAllArticles();
        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS."/ArticleManagementTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>