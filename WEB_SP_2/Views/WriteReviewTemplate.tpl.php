<?php

//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS."/ZakladHTML.class.php");
$tplHeaders = new ZakladHTML();

?>
    <!-- ------------------------------------------------------------------------------------------------------- -->

    <!-- Vypis obsahu sablony -->
<?php
// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);
if ($tplData['logged'] == true) {
    $tplHeaders->getLoggedMenu($tplData['user']["jmeno"], $tplData['user']["id_pravo"]);
} else {
    $tplHeaders->getMenu();
}
?>
    <div class="container rounded m-4" id="psani_recenze">
        <div class="row centered-form">
            <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
                <div class="panel panel-default">
                    <form role="form" method="post">
                        <div class="panel-heading">
                            <h3 class="panel-title">Napsat recenzi</h3>
                        </div>
                        <div class="row m-3">
                            <div><label>Kvalita: </label></div>
                            <div class="col-lg-8">
                                <select class="form-control" name="kvalita" id="kvalita">
                                    <option value="1">1 - výborně</option>
                                    <option value="2">2 - velmi dobře</option>
                                    <option value="3">3 - dobře </option>
                                    <option value="4">4 - dostatečně</option>
                                    <option value="5">5 - nedostatečně</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div><label>Struktura: </label></div>
                            <div class="col-lg-8">
                                <select class="form-control" name="struktura" id="struktura">
                                    <option value="1">1 - výborně</option>
                                    <option value="2">2 - velmi dobře</option>
                                    <option value="3">3 - dobře </option>
                                    <option value="4">4 - dostatečně</option>
                                    <option value="5">5 - nedostatečně</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div><label>Jazyk: </label></div>
                            <div class="col-lg-8">
                                <select class="form-control" name="jazyk" id="jazyk">
                                    <option value="1">1 - výborně</option>
                                    <option value="2">2 - velmi dobře</option>
                                    <option value="3">3 - dobře </option>
                                    <option value="4">4 - dostatečně</option>
                                    <option value="5">5 - nedostatečně</option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div><label>Článek: </label></div>
                            <div class="col-lg-8">
                                <select class="form-control" name="id_clanek" id="id_clanek">
                                    <?php
                                    foreach ($tplData['articles'] as $article){
                                        echo "<option value='".$article['id_clanek']."'>". $article['id_clanek']." - ". $article['titulek'] ."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <textarea maxlength="200" class="form-control rounded-1" name="rating" id="rating" rows="10" style="max-height: 200px" placeholder="Stručné hodnocení..."></textarea>
                            </div>
                            <input type="hidden" name="action" value="review">
                            <input type="submit" name="potvrzeni" value="Zveřejnit" class="btn btn-info btn-block">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

$tplHeaders->getHTMLFooter();
