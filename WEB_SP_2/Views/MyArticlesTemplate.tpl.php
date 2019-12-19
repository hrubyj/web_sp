<?php

//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS."/ZakladHTML.class.php");
$tplHeaders = new ZakladHTML();

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);

//urceni menu
if ($tplData['logged'] == true){
    $tplHeaders->getLoggedMenu($tplData['user']["jmeno"], $tplData['user']["id_pravo"]);
} else {
    $tplHeaders->getMenu();
}

$res = "<div class='container rounded' id='uzivatele'><table class='table col-lg-12 m-3 rounded table-responsive table-hover'><thead><tr><th>Titulek</th><th>text</th></tr></thead>";
// projdu data a vypisu radky tabulky
foreach($tplData['articles'] as $a){
    if ($a['uzivatel_id_uzivatel'] == $tplData['user']["id_uzivatel"]) {
        $res .= "<tr><td>$a[titulek]</td><td>$a[text]</td>"
            . "<td><form method='post'>"
            . "<input type='hidden' name='id_clanek' value='$a[id_clanek]'>"
            . "<button class='btn btn-danger' type='submit' name='action' value='delete'>Smazat</button>"
            . "</form></td></tr>";
    }
}
$res .= "</table></div>";
echo $res;

$tplHeaders->getHTMLFooter()

?>