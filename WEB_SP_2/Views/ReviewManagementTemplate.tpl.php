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

$res = "<div class='container rounded' id='recenze'><table class='table col-lg-8 m-3 rounded table-responsive table-hover'><thead><tr>
<th>ID článku</th><th>Hodnocení</th><th>Kval.</th><th>Struk.</th><th>Jazyk</th></tr></thead>";
// projdu data a vypisu radky tabulky
foreach($tplData['reviews'] as $a){
        $res .= "<tr><td>$a[clanek_id_clanek]</td>"
            . "<td class='text'><span>$a[hodnoceni]</span></td>"
            . "<td>$a[kvalita]</td><td>$a[struktura]</td><td>$a[jazyk]</td>"
            . "<td><form method='post'>"
            . "<input type='hidden' name='id_recenze' value='$a[id_recenze]'>"
            . "<button class='btn btn-danger' type='submit' name='action' value='delete'>Smazat</button>"
            . "</form></td></tr>";
}
$res .= "</table></div>";
echo $res;

$tplHeaders->getHTMLFooter()

?>