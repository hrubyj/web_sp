<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class IntroductionController implements IController {

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
        $tplData['articles'] = $this->db->getAllArticles();
        $tplData['autors'] = $this->db->getAllUsers();

        // zpracovani odeslanych formularu
        if(isset($_POST['action'])){
            if($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])){
                $res = $this->login->login($_POST['login'], $_POST['heslo']);
                if($res){
                    echo "OK: Uživatel byl přihlášen.";
                } else {
                    echo "ERROR: Přihlášení uživatele se nezdařilo.";
                }
            }
            else if($_POST['action'] == 'logout'){
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
            }
            else {
                echo "WARNING: Neznámá akce.";
            }
            echo "<br>";
        }

        if($tplData['logged'] = $this->login->isUserLogged()){
            $tplData['user'] = $this->login->getLoggedUserData();
        }

        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS."/IntroductionTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }
    
}

?>