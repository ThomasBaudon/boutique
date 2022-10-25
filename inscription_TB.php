<!-- ------------------------- TRAITEMENT ------------------------------- -->
<?php

  require_once('./inc/init.php');

    //ÉTAPE 1

    /* Vérifier la taile du pseudo (min 3 / max 20 cacarectères) - strlen() */
    /* En utilisant la fonction  preg_match() pour vérifier  si le pseudo contient les caractèrtes autorisés */
    /* '#^[a-zA-Z0-9._-]+$#' */
    /* Vérifier si le pseudo est déjà dans la BDD ( 2 users ne peuvent pas avoir le même pseudo) */
    $error="";

    if($_POST){
        /* -------------------------------------> VARIABLES */

       

        $pseudo  = "";
        $pseudo = $_POST['pseudo'];

        $regExPattern = '#^[a-zA-Z0-9._-]+$#';
        $r = $pdo->query("SELECT pseudo FROM membre WHERE pseudo = $pseudo");


        /* En utilisant la fonction password_hash(), crypter le mot de passe de l'user dans la bdd */
        $password = $_POST['mdp'];
        // $passwordHashed = hash("sha512", $password);
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        if(!empty($_POST['pseudo'])){
            /* -------------------------------------> LOGIQUE */

            /* LENGTH */
            if(strlen($pseudo)< 3 || strlen($pseudo) > 20)
            {
                $content = "<div class=\"alert alert-danger\"> attention votre pseudo doit contenir au minimum 3 caractères et au maximum 20</div>";
                $error .= $content;
                
            }
            /* REGEX */
            elseif(!preg_match($regExPattern, $pseudo))
            {
                $content = "<div class=\"alert alert-danger\"> attention votre pseudo ne doit contenir que des lettres, chiffres et le '-'</div>";
                $error .= $content;
            }
            /* PSEUDO DISPO */
            elseif($r-> rowCount() >=1 )
            {
                $content = "<div class=\"alert alert-danger\"> Attention, ce pseudo est déjà utilisé sur notre site, veuillez en choisir un autre</div>";
                $error .= $content;
            }
            /* ALL IS OK */
            else
            {
                echo $pseudo."<br>";
                echo $password."<br>";
                echo $passwordHashed."<br>";
            }

        }

       
    }

?>



<!-- ------------------------- AFFICHAGE ------------------------------- -->
<?php
  require_once('./inc/header.inc.php');
  require_once('./inc/nav.inc.php');
?>

<h1 class="m-3 text-center">Inscription</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">


        <?php echo $error;?>

        <form method="POST" action="">
            <!-- PSEUDO -->
            <div class="mb-3">
                <label for="Pseudo" class="form-label">Choisissez un pseudo</label>
                <input type="text" class="form-control pseudo" placeholder="Pseudo" aria-label="Pseudo" id="pseudo" name="pseudo">
            </div>

            <!-- Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Choisissez un nom</label>
                <input type="text" class="form-control nom" placeholder="nom" aria-label="nom" id="nom" name="nom">
            </div>

            <!-- Prenom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Choisissez un prénom</label>
                <input type="text" class="form-control prenom" placeholder="prénom" aria-label="prenom" id="prenom" name="prenom">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Votre e-mail</label>
                <input type="email" class="form-control" id="email" aria-describedby="email" name="email">
            </div>

            <!-- GENRE -->
            <div class="input-group mb-3">
                <label class="input-group-text" for="genre-select">Vous êtes</label>
                <select class="form-select" id="genre-select" name="genre-select">
                    <option selected>Choix...</option>
                    <option value="m">Homme</option>
                    <option value="f">Femme</option>
                </select>
            </div>

            <!-- Adresse -->
            <div class="mb-3">
                <label for="adresse" class="form-label">Votre adresse</label>
                <input type="text" class="form-control adresse" placeholder="adresse" aria-label="adresse" id="adresse" name="adresse">
            </div>

            <!-- zip -->
            <div class="mb-3">
                <label for="zip" class="form-label">Code postal</label>
                <input type="text" class="form-control zip" placeholder="Code postal" aria-label="zip" id="zip" name="zip" min="1" max="6">
            </div>

            <!-- ville -->
            <div class="mb-3">
                <label for="ville" class="form-label">Votre ville</label>
                <input type="text" class="form-control ville" placeholder="ville" aria-label="ville" id="ville" name="ville">
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp">
            </div>

            <!-- Confirmation Mot de passe -->
        <!-- <div class="mb-3">
                <label for="password2" class="form-label">Confirmez le password</label>
                <input type="password" class="form-control" id="password2">
            </div> -->

            <!-- Checkbox -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">J’accepte les conditions et les CGV</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">S’INSCRIRE</button>
            </form>
        </div>
    </div>
</div>



<?php
  require_once('./inc/footer.inc.php');
?>