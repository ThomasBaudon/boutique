<?php

  require_once('../inc/init.php');

  if(!userIsAdmin()){
    header('location:../index.php');
    exit();
}

  require_once('../inc/header.inc.php');
  require_once('inc/header-admin.php');
?>

<h1 class="m-3  text-center">DASHBOARD</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10 text-center">
        <ul class="list-group">
            <li class="list-group-item">
                <a href="gestion-commandes.php" class="nav-link">Gestion commandes</a>
            </li>
            <li class="list-group-item">
                <a href="gestion-profil.php" class="nav-link">Gestion profils</a>
            </li>
            <li class="list-group-item">
                <a href="ajout_produit.php" class="nav-link">Ajout produits</a>
            </li>
            
            <li class="list-group-item">
                <a href="liste_produits.php" class="nav-link">Liste produits</a>
            </li>
            
        </ul>
        </div>
    </div>
</div>

<?php
  require_once('../inc/footer.inc.php');
?>