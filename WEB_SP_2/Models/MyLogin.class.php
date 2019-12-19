<?php

require_once DIRECTORY_MODELS . "/MySession.class.php";
require_once DIRECTORY_MODELS . "/DatabaseModel.php";

/**
 * Trida pro spravu prihlaseni uzivatelu.
 */
class MyLogin
{
    /** @var MySession Objekt pro spravu session. */
    private $session;
    /** @var MyDatabase Objekt pro spravu databaze. */
    private $db;
    /** @var string $userSessionKey Klicem pro data uzivatele, ktera jsou ulozena v session. */
    private $userSessionKey = "current_user_id";

    function __construct()
    {
        $this->session = new MySession();
        $this->db = new DatabaseModel();
    }

    /** Vrati, jestli je nejaky uzivatel prihlasen.
     * @return bool true = prihlasen / false = neprihlasen
     */
    function isUserLogged()
    {
        return $this->session->isSessionSet($this->userSessionKey);
    }

    /** Overi, jestli muze byt uzivatel prihlasen, pripadne ho prihlasi.
     * @param string $login login uzivatele
     * @param string $heslo heslo uzivatele
     * @return bool             Byl prihlasen?
     */
    function login(string $login, string $heslo) {
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);

        $user = $this->db->getUser($login, $heslo);
        $hash = $user[0]['heslo'];
        if (count($user) && password_verify($heslo, $hash)) {
            $_SESSION[$this->userSessionKey] = $user[0]['id_uzivatel'];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Odhlasi prave prihlaseneho uzivatele.
     */
    function logout()
    {
        $this->session->removeSession($this->userSessionKey);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypisu chybu.
     *
     * @return mixed|null   Data uzivatele nebo null.
     */
    public function getLoggedUserData() {
        if ($this->isUserLogged()) {
            // ziskam data uzivatele ze session
            $userId = $this->session->readSession($this->userSessionKey);
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if ($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "SEVER ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.";
                $this->logout();
                // vracim null
                return null;
            } else {
                // nactu data uzivatele z databaze
                $userData = $this->db->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=$userId");
                // mam data uzivatele?
                if (empty($userData)) {
                    // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
                    echo "ERROR: Data přihlášeného uživatele se nenachází v databázi (mohl být smazán), a proto byl uživatel odhlášen.";
                    $this->logout();
                    return null;
                } else {
                    // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
                    return $userData[0];
                }
            }
        } else {
            // uzivatel neni prihlasen - vracim null
            return null;
        }
    }

}