<?php
require_once(DIRECTORY_MODELS."/DatabaseModel.php");

/**
 * Ovladac zajistujici vypsani stranky s formulářem pro registraci.
 */
class UserRegistrationController implements IController
{
    /** @var DatabaseModel $db Sprava databaze. */
    private $db;
    /** @var MyLogin $login sprava prihlaseni. */
    private $login;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct()
    {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.php");
        require_once (DIRECTORY_MODELS ."/MyLogin.class.php");
        $this->db = new DatabaseModel();
        $this->login = new MyLogin();
    }

    /**
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle): string {
        global $tplData;
        $tplData = [];
        $tplData['title'] = $pageTitle;

        // zpracovani odeslanych formularu
        if(isset($_POST['action'])) {
            if ($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])) {
                $res = $this->login->login($_POST['login'], $_POST['heslo']);
                if ($res) {
                    echo "OK: Uživatel byl přihlášen.";
                } else {
                    echo "ERROR: Přihlášení uživatele se nezdařilo.";
                }
            } else if ($_POST['action'] == 'logout') {
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
            } else if ($_POST['action'] == 'register' && isset($_POST['loginR']) && isset($_POST['hesloR']) && isset($_POST['hesloR2'])
                && isset($_POST['jmeno']) && isset($_POST['email']) && $_POST['hesloR'] == $_POST['hesloR2']
                && $_POST['loginR'] != "" && $_POST['hesloR'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != "") {

                $this->db->addNewUser($_POST['jmeno'], $_POST['loginR'], $_POST['hesloR'], $_POST['email'], 3);
                    echo "OK: Uživatel byl přidán do databáze.";
                    echo "<br>";
                    $this->login->login($_POST['loginR'], $_POST['hesloR']);
                    echo "OK: Uživatel byl přihlášen.";
                    header('Location: index.php?page=uvod');
                } else {
                    echo "ERROR: Nebyly přijaty požadované atributy uživatele.";
                }
                echo "<br>";
            }

        if($tplData['logged'] = $this->login->isUserLogged()){
            $tplData['user'] = $this->login->getLoggedUserData();
        }

        ob_start();
        require(DIRECTORY_VIEWS."/UserRegistrationTemplate.tpl.php");
        $obsah = ob_get_clean();

        return $obsah;
    }
}

?>
