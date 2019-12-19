<?php
require_once(DIRECTORY_MODELS."/DatabaseModel.php");

/**
 * Ovladac zajistujici vypsani stranky pro psani noveho clanku.
 */
class WriteArticleController implements IController
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
        if($tplData['logged'] = $this->login->isUserLogged()){
            $tplData['user'] = $this->login->getLoggedUserData();
        }

        // zpracovani odeslanych formularu
        if(isset($_POST['action'])) {
            if ($_POST['action'] == 'logout') {
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
                echo "<br>";
                header('Location: index.php?page=uvod');
            } else if ($_POST['action'] == 'article' && isset($_POST['abstract']) && isset($_POST['titulek']) && isset($_POST['pdf'])
                && $_POST['abstract'] != "" && $_POST['titulek'] != "" && $_POST['pdf'] != "") {

                $this->db->addNewArticle($_POST['titulek'], $_POST['abstract'], $_POST['pdf'], $tplData['user']["id_uzivatel"], date( 'Y-m-d H:i:s'));
                header('Location: index.php?page=moje_clanky');
                echo "<br>";
            }

        }

        ob_start();
        require(DIRECTORY_VIEWS."/WriteArticleTemplate.tpl.php");
        $obsah = ob_get_clean();

        return $obsah;
    }
}

?>
