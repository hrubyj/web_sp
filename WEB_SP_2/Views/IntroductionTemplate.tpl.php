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
// vypis clanku
$res = "";
if(array_key_exists('articles', $tplData)) {
    foreach ($tplData['articles'] as $article) {
        foreach ($tplData['autors'] as $autor) {
            if ($article['uzivatel_id_uzivatel'] == $autor['id_uzivatel']){
                $res .= "<div class='container rounded' id='clanek'>";
                $res .= "$autor[jmeno] - <b>$article[titulek]</b>";
                $res .= "<div style='text-align:justify;'>$article[text]</div><hr>";
                $res .= "</div>";
            }
        }
    }
} else {
    $res .= "Články nenalezeny";
}
echo $res;

$tplHeaders->getHTMLFooter()

?>