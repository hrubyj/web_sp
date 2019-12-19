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
<div class="container rounded m-4" id="registrace">
    <div class="row centered-form">
        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Napsat článek</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                            <div class="form-group">
                                <input type="text" name="titulek" id="titulek" class="form-control input-sm" placeholder="Titulek" required>
                            </div>
                            <div class="form-group">
                                <textarea maxlength="500" class="form-control rounded-1" name="abstract" id="abstract" rows="10" style="max-height: 200px" placeholder="Stručný popis..."></textarea>
                            </div>
                            <div class="form-group">
                                <input type="file" accept="application/pdf" name="pdf" id="pdf" class="form-control input-sm" required>
                            </div>
                        <input type="hidden" name="action" value="article">
                        <input type="submit" name="potvrzeni" value="Publikovat" class="btn btn-info btn-block">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$tplHeaders->getHTMLFooter()

?>
