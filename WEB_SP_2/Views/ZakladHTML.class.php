<?php

/**
 * Trida pro vypis hlavicky a paticky HTML stranky.
 */
class ZakladHTML {

    /**
     *  Vytvoreni hlavicky stranky.
     *  @param string $title Nazev stranky.
     */
    public static function getHTMLHeader(string $title)
    {
        ?>
        <!doctype html>
        <html lang="cs">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php echo $title; ?></title>
            <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
            <link rel="stylesheet" href="bootstrap/font-awesome.min.css">
            <link rel="stylesheet" href="bootstrap/myStyle.css">
        </head>
        <body class="container">
        <header class="container p-3 my-3 text-danger rounded" id="hlavicka">
            <h1>Sportovní aktuality</h1>
            <p class="text-warning font-weight-bold">Závody, turnaje, zápasy a mnohem více...</p>
        </header>
        <?php
    }

    public function getLoggedMenu($user, $pravo){
        ?>
        <nav class="navbar navbar-expand-md navbar-dark sticky-top rounded" id="menu">
        <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse "  id="collapsibleNavbar">
                <ul class="navbar-nav">
        <?php
        if($pravo == 1) {
            ?>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=uvod'>Články</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=recenze'>Recenze</a>
            </li>
            <li class=\"nav-item\">
            <a class='nav-link' href='index.php?page=sprava_clanky'>Správa článků</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=sprava_recenze'>Správa recenzí</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=sprava'>Správa uživatelů</a>
            </li>
        <?php
        }

        else if($pravo == 2) {
        ?>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=uvod'>Články</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=psani_recenze'>Napsat recenzi</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=moje_recenze'>Moje recenze</a>
            </li>

        <?php
        }

        else if($pravo == 3) {
        ?>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=uvod'>Články</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=psani_clanku'>Nový článek</a>
            </li>
            <li class=\"nav-item\">
                <a class='nav-link' href='index.php?page=moje_clanky'>Moje články</a>
            </li>
    <?php
        }
    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $user ?></a>
                        <div class="dropdown-menu bg-dark">
                            <form action="" method="POST" class="text-center bg-dark">
                                <input type="hidden" name="action" value="logout">
                                <input type="submit" name="potvrzeni" value="Odhlásit" class="btn btn-outline-danger p-2">
                            </form>
                        </div>
                    </li>
                 </ul>
            </div>
        </nav>

        <?php
    }

    public function getMenu(){
        ?>
        <nav class="navbar navbar-expand-md navbar-dark sticky-top rounded" id="menu">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse "  id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Přihlášení</a>
                        <div class="dropdown-menu bg-dark">
                            <form id="login-form" method="post" class="text-left p-3 bg-dark">
                                <div class="login-form-main-message"></div>
                                <div class="main-login-form">
                                    <div class="login-group">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="login" name="login" placeholder="Login">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="heslo" name="heslo" placeholder="Heslo">
                                        </div>
                                    </div>
                                    <input type="hidden" name="action" value="login">
                                    <input type="submit" name="potvrzeni" value="Přihlásit" class="btn btn-outline-primary">
                                </div>
                                <a class="nav-link " href="index.php?page=registrace">Registrovat</a>
                            </form>
                        </div>
                    </li>
                 </ul>
            </div>
        </nav>
   <?php
    }

    /**
     *  Vytvoreni paticky.
     */
    public static function getHTMLFooter(){
    ?>
        <div class="footer">
            Jaroslav Hrubý &copy Semestrální práce KIV/WEB
        </div>

                <script src="bootstrap/jquery-3.4.1.min.js"></script>
                <script src="bootstrap/bootstrap.min.js"></script>
            </body>
        </html>
        <?php
    }

}
?>