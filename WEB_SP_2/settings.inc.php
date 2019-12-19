<?php

////// nastaveni pristupu k databazi ///////

    // prihlasovaci udaje k databazi
    /** Adresa serveru. */
    define("DB_SERVER","localhost");
    /** Nazev databaze. */
    define("DB_NAME","web_sp");
    /** Uzivatel databaze. */
    define("DB_USER","root");
    /** Heslo uzivatele databaze */
    define("DB_PASS","");

    // tabulky databaze
    /** Tabulka uzivatelu */
    define("TABLE_UZIVATEL","uzivatel");
    /** Tabulka prav */
    define("TABLE_PRAVO","pravo");
    /** Tabulka clanku */
    define("TABLE_CLANEK","clanek");
    /** Tabulka recenzi */
    define("TABLE_RECENZE","recenze");

    // adresare MVC
    /** Adresar kontroleru. */
    const DIRECTORY_CONTROLLERS = "Controllers";
    /** Adresar modelu. */
    const DIRECTORY_MODELS = "Models";
    /** Adresar sablon */
    const DIRECTORY_VIEWS = "Views";

    // dostupne webove stranky
    const WEB_PAGES = array(
        // uvodni stranka
        "uvod" => array("file_name" => "IntroductionController.class.php",
            "class_name" => "IntroductionController",
            "title" => "Články"),
        // registrace
        "registrace" => array("file_name" => "UserRegistrationController.class.php",
            "class_name" => "UserRegistrationController",
            "title" => "Registrace"),
        // psani clanku
        "psani_clanku" => array("file_name" => "WriteArticleController.class.php",
            "class_name" => "WriteArticleController",
            "title" => "Nový příspěvek"),
        // nahled clanku
        "moje_clanky" => array("file_name" => "MyArticlesController.class.php",
            "class_name" => "MyArticlesController",
            "title" => "Moje články"),
        // psani recenzi
        "psani_recenze" => array("file_name" => "WriteReviewController.class.php",
            "class_name" => "WriteReviewController",
            "title" => "Napsat Recenzi"),
        // nahled recenzi
        "recenze" => array("file_name" => "ReviewsController.class.php",
            "class_name" => "ReviewsController",
            "title" => "Recenze"),
        // moje recenze
        "moje_recenze" => array("file_name" => "MyReviewsController.class.php",
            "class_name" => "MyReviewsController",
            "title" => "Moje Recenze"),
        "sprava" => array("file_name" => "UserManagementController.class.php",
            "class_name" => "UserManagementController",
            "title" => "Správa uživatelů"),
        "sprava_clanky" => array("file_name" => "ArticleManagementController.class.php",
            "class_name" => "ArticleManagementController",
            "title" => "Správa článků"),
        "sprava_recenze" => array("file_name" => "ReviewManagementController.class.php",
            "class_name" => "ReviewManagementController",
            "title" => "Správa recenzí"),
    );

    /** defaultni webova stranka */
    const DEFAULT_WEB_PAGE_KEY = "uvod";

?>