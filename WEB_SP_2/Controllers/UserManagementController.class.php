<?php
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class UserManagementController implements IController {

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
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;
        $tplData['rights'] = $this->db->getAllRights();
        if($tplData['logged'] = $this->login->isUserLogged()){
            $tplData['user'] = $this->login->getLoggedUserData();
        }


        //// neprisel pozadavek na smazani uzivatele?
        if(isset($_POST['action'])) {
            if($_POST['action'] == 'logout'){
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
                header('Location: index.php?page=uvod');
            }
            elseif ($_POST['action'] == "delete" and isset($_POST['id_uzivatel'])) {
                $ok = $this->db->deleteFromTable(TABLE_UZIVATEL, "id_uzivatel = " . intval($_POST['id_uzivatel']));
                if ($ok) {
                    header('Location: index.php?page=sprava');
                    $tplData['delete'] = "OK: Uživatel s ID:$_POST[id_uzivatel] byl smazán z databáze.";
                } else {
                    header('Location: index.php?page=sprava');
                    $tplData['delete'] = "CHYBA: Uživatele s ID:$_POST[id_uzivatel] se nepodařilo smazat z databáze.";
                }
            } else if ($_POST['action'] == "update" and isset($_POST['id_uzivatel'])){
                $ok = $this->db->updateUser($_POST['id_uzivatel'], $_POST['id_pravo']);
                if ($ok) {
                    header('Location: index.php?page=sprava');
                    $tplData['delete'] = "OK: Uživatel s ID:$_POST[id_uzivatel] byl aktualizován.";
                } else {
                    header('Location: index.php?page=sprava');
                    $tplData['delete'] = "CHYBA: Uživatel s ID:$_POST[id_uzivatel] nebyl aktualizován.";
                }
            }
        }

        //// nactu aktulani data uzivatelu
        $tplData['users'] = $this->db->getAllUsers();

        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/UserManagementTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>