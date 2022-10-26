<!-- ------------------------- TRAITEMENT ------------------------------- -->

<?php require_once './inc/init.php'; ?>

<?php 
    $error = '';

    if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
        // session_destroy();
        session_unset();
        header('location:index.php');
    }

    if($_POST){

        if(!empty($_POST['pseudo'])){
            // si le pseudo n'est pas vide, je fais uen requête pour récupérer les infos envoyées en POST
            // Vérification si le pseudo est enregistré dans la BDD
            $req = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");

            // si le rowCount() supérieur à 1 alors il y'a un user qui a ce pseudo
            if($req->rowCount() >=1)
            {
                // $membre renvoie le résultat dans un tableau
                $membre = $req->fetch(PDO::FETCH_ASSOC); // Je fetch pour récupérér les infos dans un tableau

                // je vérifie si le mdp envoyé en POST correspond au mdp que j'ai dans mon tableau $membre qui contient les infos du memebre
                if(password_verify($_POST['mdp'], $membre['mdp'])){

                    // Je créé une sessions que j'appelle 'membre' pour stocker les infos de l'user
                    $_SESSION['membre']['id_membre']= $membre['id_membre'];
                    $_SESSION['membre']['pseudo']= $membre['pseudo'];
                    $_SESSION['membre']['nom']= $membre['nom'];
                    $_SESSION['membre']['prenom']= $membre['prenom'];
                    $_SESSION['membre']['email']= $membre['email'];
                    $_SESSION['membre']['civilite']= $membre['civilite'];
                    $_SESSION['membre']['adresse']= $membre['adresse'];
                    $_SESSION['membre']['code_postal']= $membre['code_postal'];
                    $_SESSION['membre']['ville']= $membre['ville'];
                    $_SESSION['membre']['statut']= $membre['statut'];

                    header('location:profil.php');

                }else{
                    $error .= '<div class="alert alert-danger">Le mot de passe n\'existe pas</div>';
                }
            }else{
                $error .= '<div class="alert alert-danger">Le pseudo n\'existe pas</div>';
            }
            
        }

    }

?>


<!-- ------------------------- AFFICHAGE ------------------------------- -->
<?php
  require_once('./inc/header.inc.php');
  require_once('./inc/nav.inc.php');
?>
<h1 class="m-3 text-center">Page de connexion</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">

        <?php echo $error;?>

        <form  method="POST" action="">
            <div class="mb-3">
                <label for="pseudo" class="form-label">Adresse mail ou pseudo</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Rentrez votre pseudo">

            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Rentrez votre Mot de passe">
            </div>
            
            <button type="submit" class="btn btn-primary">SE CONNECTER</button>
        </form>


        </div>
    </div>
</div>



<?php
  require_once('./inc/footer.inc.php');
?>