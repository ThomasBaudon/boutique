<?php
require_once('./inc/init.php');


if(isset($_POST['ajoute_panier'])){
    $req = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
    $produit = $req->fetch(PDO::FETCH_ASSOC);

    var_dump($produit);
}



require_once('./inc/header.inc.php');
require_once('./inc/nav.inc.php');
?>


<h1> Panier</h1>

<?php  require_once('./inc/footer.inc.php'); ?>