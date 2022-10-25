<!-- ------------------------- TRAITEMENT ------------------------------- -->
<?php

require_once './inc/init.php';

if(!userConnected()){
    header('location:connexion.php');
    // exit() stoppe l'exécution du code
    exit();
}

if(userIsAdmin()){
    $content .= '<div class="alert alert-success" role="alert">Vous êtes connecté en tant que ADMIN</div>';

    $id_membre = $_SESSION['membre']['id_membre'];
    $pseudo = $_SESSION['membre']['pseudo'];
    $nom = $_SESSION['membre']['nom'];
    $prenom = $_SESSION['membre']['prenom'];
    $email = $_SESSION['membre']['email'];
    $civilite = $_SESSION['membre']['civilite'];
    $adresse = $_SESSION['membre']['adresse'];
    $code_postal = $_SESSION['membre']['code_postal'];
    $ville = $_SESSION['membre']['ville'];
    $statut = $_SESSION['membre']['statut'];
}else{
    $content .= '<div class="alert alert-warning" role="alert">Vous êtes connecté en tant que MEMBRE</div>';
    $id_membre = $_SESSION['membre']['id_membre'];
    $pseudo = $_SESSION['membre']['pseudo'];
    $nom = $_SESSION['membre']['nom'];
    $prenom = $_SESSION['membre']['prenom'];
    $email = $_SESSION['membre']['email'];
    $civilite = $_SESSION['membre']['civilite'];
    $adresse = $_SESSION['membre']['adresse'];
    $code_postal = $_SESSION['membre']['code_postal'];
    $ville = $_SESSION['membre']['ville'];
    $statut = $_SESSION['membre']['statut'];
}

// SI une session est en cours fais ceci
// if(isset($_SESSION['membre']))
// {
//     
// }
// // Sinon fais ça
// else{
//     header('location:inscription.php');
// }



?>


<!-- ------------------------- AFFICHAGE ------------------------------- -->
<?php
  require_once('./inc/header.inc.php');
  require_once('./inc/nav.inc.php');
?>

<h1 class="m-3 text-center">Vous êtes connecté en tant que : <br> <?php echo $pseudo; ?></h1>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-10 text-center">

            <?php echo $content; ?>

            <p>Nom : <?php echo $nom; ?></p>
            <p>Prénom : <?php echo $prenom; ?></p>
            <p>Civilité : <?php echo $civilite; ?></p>
            <p>Mail : <?php echo $email; ?></p>
            <p>Adresse : <?php echo $adresse; ?></p>
            <p>Code postal : <?php echo $code_postal; ?></p>
            <p>Ville : <?php echo $ville; ?></p>
            <p>Statut : <?php echo $statut; ?></p>

        </div>
    </div>
</div>


<?php
  require_once('./inc/footer.inc.php');
?>