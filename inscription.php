<!-- ------------------------- TRAITEMENT ------------------------------- -->
<?php

require_once './inc/init.php';

$error = '';

if(!empty($_POST)) {

    foreach($_POST as $key =>$valeur){
        $_POST[$key] = htmlspecialchars(addslashes($valeur));
    }

    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];



    // PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO PSEUDO
    // Vérification de la longueur du pseudo
    $pseudo = addslashes($pseudo);
    if(strlen($pseudo) <3 || strlen($pseudo) > 20) {
        $error .= '<div class="alert alert-danger">Le pseudo doit contenir entre 3 et 20 caractères</div>';
    }

    // Vérification des caractères autorisés (regex)
    
    $regExPattern = '#^[a-zA-Z0-9._-]+$#';
    if(!preg_match($regExPattern, $pseudo)) {
        $error .= '<div class="alert alert-danger">Caractères autorisés : a-z A-Z 0-9 . _ -</div>';
    }

    // Vérification de la disponibilité du pseudo dans la BDD
    $r = $pdo->query("SELECT * FROM membre WHERE pseudo = '$pseudo'");
    if($r->rowCount() >= 1) {
        $error .= '<div class="alert alert-danger">Le pseudo est déjà pris </div>';
    }
    
    // MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP MDP
    // Vérification de la longueur du mdp
    if(strlen($mdp) <3 || strlen($mdp) > 20) {
        $error .= '<div class="alert alert-danger">Le mot de passe doit contenir entre 3 et 20 caractères</div>';
    }

                
    // Hacher le mdp
    $mdp = password_hash($mdp, PASSWORD_DEFAULT);

    // Insérer l'user ds la bdd
    if(empty($error)) {
        $pdo->query("INSERT INTO membre (pseudo,mdp,nom,prenom,email,civilite,ville,code_postal,adresse) VALUES ('$_POST[pseudo]', '$mdp', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[ville]', '$_POST[zip]', '$_POST[adresse]')");
        $error .= '<div class="alert alert-success">Vous êtes inscrit</div>';
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
                <label class="input-group-text" for="civilite">Vous êtes</label>
                <select class="form-select" id="civilite" name="civilite">
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