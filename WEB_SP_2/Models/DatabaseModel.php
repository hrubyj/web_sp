<?php

/**
 * Vlastni trida spravujici databazi.
 */
class DatabaseModel {

    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private $pdo;

    /**
     * Database constructor.
     * Inicializace pripojeni k databazi.
     */
    public function __construct(){
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
    }

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz){
        $res = $this->pdo->query($dotaz);
        if ($res) {
            return $res;
        } else {
            $error = $this->pdo->errorInfo();
            echo $error[2];
            return null;
        }
    }

    /**
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        $obj = $this->executeQuery($q);
        if($obj == null){
            return [];
        }

        return $obj->fetchAll();
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     */
    public function deleteFromTable(string $tableName, string $whereStatement){
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Ziskani zaznamu vsech uzivatelu aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(){
        $users = $this->selectFromTable(TABLE_UZIVATEL, "", "id_uzivatel");
        return $users;
    }

    /**
     *  Ziskani zaznamu vsech clanky aplikace.
     *
     *  @return array Pole se vsemi clanky.
     */
    public function getAllArticles():array {
        $articles = $this->selectFromTable(TABLE_CLANEK, "", "datum DESC");
        return $articles;
    }

    /**
     *  Ziskani zaznamu vsech recenzi aplikace.
     *
     *  @return array Pole se vsemi recenzemi.
     */
    public function getAllReviews():array {
        $reviews = $this->selectFromTable(TABLE_RECENZE, "", "datum DESC");
        return $reviews;
    }

    /**
     * Ziskani zaznamu vsech prav aplikace.
     *
     * @return array    Pole se vsemi pravy.
     */
    public function getAllRights(){
        $users = $this->selectFromTable(TABLE_PRAVO, "", "vaha ASC, nazev ASC");
        return $users;
    }

    /**
     * Nalezne uzivatele s danym loginem a heslem a vrati je.
     * @param string $login   Login.
     * @param string $heslo   Heslo.
     * @return array
     */
    public function getUser($login, $heslo) {
        $sql = "SELECT * FROM " . TABLE_UZIVATEL . " WHERE login=:uLogin;";
        $vystup = $this->pdo->prepare($sql);
        $vystup->bindValue(":uLogin", $login);
        if ($vystup->execute()) {
            return $vystup->fetchAll();
        } else {
            return null;
        }
    }


    /**
     * Vytvoreni noveho uzivatele v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $jmeno, string $login, string $heslo, string $email, int $idPravo = 3){
        $jmeno = htmlspecialchars($jmeno);
        $login = htmlspecialchars($login);
        $heslo = htmlspecialchars($heslo);
        $heslo = password_hash($heslo, PASSWORD_BCRYPT);
        $email = htmlspecialchars($email);

        $sql = "INSERT INTO ".TABLE_UZIVATEL." (jmeno, login, heslo, email, id_pravo) VALUES (?,?,?,?,?)";
        $preparedStatement = $this->pdo->prepare($sql);
        if($preparedStatement->execute([$jmeno, $login, $heslo, $email, $idPravo])) {
            echo "Uživatel přídán do databáze.";
        } else {
            echo "Přidání uživatele se nezdařilo.";
        }
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUser(int $idUzivatel, int $idPravo){
        $updateStatementWithValues = "id_pravo='$idPravo'";
        $whereStatement = "id_uzivatel=$idUzivatel";
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }


    /**
     * Prida do databaze clanek s patricnymi atributy
     * @param string $titulek       Titulek
     * @param string $text          Text
     * @param string $pdf           pdf soubor
     * @param string $id_uzivatel   ID autora
     * @return bool                 Pridan uspesne?
     */
    public function addNewArticle(string $titulek, string $text, string $pdf, int $id_uzivatel, $date){
        $titulek = htmlspecialchars($titulek);
        $text = htmlspecialchars($text);

        $q = "INSERT INTO ".TABLE_CLANEK." (titulek, text, pdf, uzivatel_id_uzivatel, datum) VALUES (:kUzivatel, :kText, :kPdf, :kUzivatel_id, :kDatum);";
        $vystup = $this->pdo->prepare($q);
        if($vystup->execute(array( "kUzivatel" => $titulek, "kText" => $text , "kPdf" => $pdf, "kUzivatel_id" => $id_uzivatel, "kDatum" => $date))){
            echo "Článek přídán do databáze.";
        } else {
            echo "Přidání článku se nezdařilo.";
        }
    }

    /**
     * Prida do databaze recenzi s patricnymi atributy.
     * @param int $kvalita          hodnoceni kvality
     * @param int $struktura        hodnoceni struktury
     * @param int $jazyk            hodnoceni pouziteho jazyka
     * @param string $hodnoceni     slovni hodonoceni
     * @param int $id_clanek        id hodnoceneho clanku
     * @param int $id_uzivatel      id hodnoticiho uzivatele
     * @return bool                 Pridana uspesne
     */
    public function addNewReview(int $kvalita, int $struktura, int $jazyk, string $hodnoceni, $datum, int $id_clanek, int $id_uzivatel){
        $hodnoceni = htmlspecialchars($hodnoceni);

        $sql = "INSERT INTO ".TABLE_RECENZE." (kvalita, struktura, jazyk, hodnoceni, datum, clanek_id_clanek, uzivatel_id_uzivatel) VALUES (?,?,?,?,?,?,?)";
        $preparedStatement = $this->pdo->prepare($sql);
        if ($preparedStatement->execute([$kvalita, $struktura, $jazyk, $hodnoceni, $datum, $id_clanek, $id_uzivatel])){
            echo "Recenze přídána do databáze.";
        } else {
            echo "Přidání recenze se nezdařilo.";
        }
    }
}
?>