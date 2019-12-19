<?php

//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS ."/ZakladHTML.class.php");
$tplHeaders = new ZakladHTML();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php
// muze se hodit:
//<form method='post'>
//    <input type='hidden' name='id_user' value=''>
//    <button type='submit' name='action' value='delete'>Smazat</button>
//</form>

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);
if ($tplData['logged'] == true) {
    $tplHeaders->getLoggedMenu($tplData['user']["jmeno"], $tplData['user']["id_pravo"]);
} else {
    $tplHeaders->getMenu();
}
// mam vypsat hlasku?
if(isset($tplData['delete'])){
    echo "<div class='alert'>$tplData[delete]</div>";
}

$res = "<div class='container rounded' id='uzivatele'><table class='table col-lg-12 m-3 rounded table-responsive table-hover'><thead><tr>
<th>ID</th><th>Jméno</th><th>Login</th><th>E-mail</th><th>Právo</th></tr></thead>";
// projdu data a vypisu radky tabulky
foreach($tplData['users'] as $u){
    $res .= "<tr><td>$u[id_uzivatel]</td><td>$u[jmeno]</td><td>$u[login]</td><td>$u[email]</td>"
        ."<td><form class='form-inline' method='post'>"
        ."<input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>"
        ."<select class='form-control' name='id_pravo' id='id_pravo'>";
            foreach ($tplData['rights'] as $right){
                if($right['id_pravo'] == $u['id_pravo']){
                    $res .= "<option value='".$right['id_pravo']."' selected>". $right['nazev'] ."</option>";
                } else {
                    $res .= "<option  value='".$right['id_pravo']."'>". $right['nazev'] ."</option>";
                }
            }
        $res .= "</select><button class='btn btn-dark' type='submit' name='action' value='update'>Uložit</button>"
                ."<button class='btn btn-danger' type='submit' name='action' value='delete'>Smazat</button></form></td></tr>";

}
$res .= "</table></div>";
echo $res;

// paticka
$tplHeaders->getHTMLFooter()

?>