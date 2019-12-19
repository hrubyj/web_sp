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

$res = "<div class='container rounded' id='vsechny_recenze'><table class='table col-lg-12 m-2 rounded table-responsive table-hover'><thead><tr>
<th>ID článku</th><th>Autor recenze</th><th>Hodnocení</th><th>Kval.</th><th>Struk.</th><th>Jazyk</th></tr></thead>";
// projdu data a vypisu radky tabulky
foreach($tplData['reviews'] as $r){
    foreach ($tplData['autors'] as $a) {
        if ($r['uzivatel_id_uzivatel'] == $a["id_uzivatel"]) {
            $res .= "<tr><td>$r[clanek_id_clanek]</td>"
                . "<td>$a[jmeno]</td><td class='text'><span>$r[hodnoceni]</span></td>"
                . "<td>$r[kvalita]</td><td>$r[struktura]</td><td>$r[jazyk]</td>"
                . "<td>"
                . "</td></tr>";
        }
    }
}
$res .= "</table></div>";
echo $res;

$tplHeaders->getHTMLFooter()

?>