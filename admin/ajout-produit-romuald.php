<!-----------------------PARTIE TRAITEMENT -------------------------------->

<?php require_once('../inc/init.php'); ?>

<?php
// Verifier si la SESSION['membre'] existe et est admin sinon il n'aura pas accès au backoffice donc je le renvoi s'il n'a pas les autorisations nécéssaire
if (!userIsAdmin()) {
    header('location:../index.php');
    exit();
}


if ($_POST) {

    // Si nous sommes dans le cas d'une modification alors on garde la photo d'origine
    //photo_actuelle est le name de la photo d'origine dans l'input hidden 
    if (isset($_GET['action']) && $_GET['action'] == 'modification') {
        $img_bdd = $_POST['photo_actuelle'];
    }

    // Faire une addslashes sur tous les champs pour éviter les apostrophes sinon cela va générer une erreur lors de l'enrégistrement
    // Pour tous les $_POST as key=>value alors $_POST[$key]= htmlspecialchars(addslashes($value))
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars(addslashes($value));
    }

    // si la photo n'est pas vide je récupère les infos de la photo grâce à la superglobale $_FILES
    if (!empty($_FILES['photo']['name'])) {

        // Je crée le nom de la photo en concatenant le timestamp (time()) avec la référence produit fournit et le nom de l'image ajouté . J'ai fait cela afin d'éviter que plusieurs produits aient le même nom car le time() sera toujours diférent même si le nom de l'image est le même

        $nom_img = time() . '_' . $_POST['reference'] . '_' . $_FILES['photo']['name'];

        // j'ajoute les chemins pour l'ajout des photos

        // chemin 1 - vers le dossier photo . Pour cela je vais utilisé la constante RACINE qui est dans mon  fichier init
        $img_doc = URL . "photo/$nom_img";

        // chemin 2 - vers la bdd . Pour cela je vais utilisé la constante URL qui est dans mon fichier init
        $img_bdd = BASE . "photo/$nom_img";


        if ($_FILES['photo']['size'] <= 8000000) { // Si la taille de la photo est inférieur ou égale à 8Mo

            //Je récupère l'extension de l'image
            $data = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

            //$ext = $data['extension'];

            // Je déclare un tableau d'extension autorisées
            $tab = ['jpg', 'png', 'jpeg'];

            // Si l'extension de l'image de l'image se trouve dans le tableau d'extension avec in_array()
            if (in_array($data, $tab)) {

                // Enfin pour enrégistrer la photo nous allons utiliser fonction copy()
                // copy() Fait une copie du fichier from vers le fichier to.
                // Copy prend 2 arguments 1: le nom temmporaire $_FILES['photo']['tmp_name'] et 2: l'emplacement où la photo sera mise $photo_dossier (dans le dossier photo de ma boutique)
                copy($_FILES['photo']['tmp_name'], $img_doc);

            } else { // Sinon le format ne se trouve pas dans mon tableau de format autorisé
                $content .= '<div class="alert alert-danger" role="alert">Format non autorisé</div>';
            }
        } else { // Sinon la taille est supérieur à 8Mo

            $content .= '<div class="alert alert-danger" role="alert">Vérifier taille de votre image</div>';
        }
    }


    // Si nous sommes dans le cas d'une modification
    if (isset($_GET['action']) && $_GET['action'] == 'modification') {

        $pdo->query("UPDATE produit 
                        SET reference = '$_POST[reference]', categorie = '$_POST[categorie]', titre = '$_POST[titre]', description = '$_POST[description]', couleur = '$_POST[couleur]', taille = '$_POST[taille]', public = '$_POST[public]', photo = '$img_bdd', prix = '$_POST[prix]', stock = '$_POST[stock]'
                        WHERE id_produit = '$_POST[id_produit]'");

        header('location:gestion_produits.php'); // je renvoi l'user sur le même fichier pour ne pas avoir infos du produit qui vient d'être update par défaut dans l'url

    } else { //Sinon nous sommes dans le cas d'un ajout

        // J'ajoute une image par défaut à mon produit

        if (empty($img_bdd)) {// Si $img_bdd est vide alors il n'y a pas d'image

            $img_bdd = 'http://localhost/phpcompiegne/boutique/photo/1666679402_A1632ZHJzfez_image_not_found.png'; // Ceci est une image par défaut que j'ai ajouté dans ma bdd

            $content .= '<div class="alert alert-info" role="alert text-center">Vous avez une image par défaut pour le produit</div>';
        }

        $pdo->query("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES ('$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[public]', '$img_bdd', '$_POST[prix]', '$_POST[stock]')");
    }
}



// s'il y a une action dans l'url et que cette action = suppression alors je fais une requête pour supprimer le produit

if (isset($_GET['action']) && $_GET['action'] == 'suppression') {

    $pdo->query("DELETE 
                    FROM produit 
                    WHERE id_produit = '$_GET[id_produit]'");

    header('location:gestion_produits.php'); // je renvoi l'user sur le même fichier pour ne pas avoir infos du produit qui vient d'être delete par défaut dans l'url
}


// Requete pour avoir tous les produits de la bdd

$r = $pdo->query("SELECT * FROM produit");

// rowCount() renvoie le nombre de lignes affectées par la dernière instruction (donc le nombre produits)
if($r->rowCount() >1){ // Si j'ai plus de 1 produit dans ma boutique j'affiche Liste des n produits
    $content .= '<h1 class="text-center display-4">Liste des ' . $r->rowCount() . ' produits de la boutique</h1>';
}else{ //Sinon j'affiche Liste de 1 produit
    $content .= '<h1 class="text-center display-4">Liste de ' . $r->rowCount() . ' produit de la boutique</h1>';
}




$content .= '<table class="table table-striped"><tr>';
// Faire une boucle pour afficher les produits dans la table

// tant que $i est inférieur à la liste des colones alors on va tourner autant de fois qu'il y a de colone à afficher

for ($i = 0; $i < $r->columnCount(); $i++) {

    //getColumnMeta() Récupère les métadonnées d'une colonne indexée à 0 dans un jeu de résultats sous la forme d'un tableau associatif.

    $colone = $r->getColumnMeta($i); // Récupérer les infos de chaque colone (les en-tête) . Tout le résultat sera dans un tableau

    $content .= '<th>' . $colone['name'] . '</th>';
}

$content .= '<th>Update</th>';
$content .= '<th>Delete</th>';

$content .= '</tr>';


// tant qu'il y a des lignes ( des données à afficher) Je vais récupérer le contenu de chaque ligne
while ($row = $r->fetch(PDO::FETCH_ASSOC)) {
    $content .= '<tr>';

    foreach ($row as $key => $value) {
        if ($key == 'photo') {
            $content .= "<td class=\"align-middle\"><img src=\"$value\" width=\"60\"></td>";
        } else {
            // Ici je coupe la longueur du texte qui sera affiché avec substr()
            $value = substr($value, 0, 100);
            $content .= "<td class=\"align-middle\">$value</td>";
        }
    }

    $content .= "<td class=\"align-middle\"><a href=?action=modification&id_produit=$row[id_produit]>update</a></td>";
    $content .= "<td class=\"align-middle\"><a href=?action=suppression&id_produit=$row[id_produit]>Delete</a></td>";

    $content .= '</tr>';
}

$content .= '</table>';

$content .= '<hr><hr><hr>';


// s'il y a une action dans l'url et que cette action = modification alors je fais une requête pour modifierr le produit

if (isset($_GET['action']) && $_GET['action'] == 'modification') {

    $r = $pdo->query("SELECT *
                    FROM produit 
                    WHERE id_produit = '$_GET[id_produit]'");

    $produit_actuel = $r->fetch(PDO::FETCH_ASSOC);
}

// Utiliser les conditions ternaire pour pré-remplir les champs 

// si l'id produit_actuel existe alors alors je le récupère sinon je mets rien dans la
$id_produit = (isset($produit_actuel['id_produit'])) ? $produit_actuel['id_produit'] : '';
$reference = (isset($produit_actuel['reference'])) ? $produit_actuel['reference'] : '';
$categorie = (isset($produit_actuel['categorie'])) ? $produit_actuel['categorie'] : '';
$titre = (isset($produit_actuel['titre'])) ? $produit_actuel['titre'] : '';
$description = (isset($produit_actuel['description'])) ? $produit_actuel['description'] : '';
$couleur = (isset($produit_actuel['couleur'])) ? $produit_actuel['couleur'] : '';
$taille = (isset($produit_actuel['taille'])) ? $produit_actuel['taille'] : '';
$public = (isset($produit_actuel['public'])) ? $produit_actuel['public'] : '';
$photo = (isset($produit_actuel['photo'])) ? $produit_actuel['photo'] : '';
$prix = (isset($produit_actuel['prix'])) ? $produit_actuel['prix'] : '';
$stock = (isset($produit_actuel['stock'])) ? $produit_actuel['stock'] : '';


?>




<!-----------------------PARTIE AFFICHAGE -------------------------------->

<?php require_once('inc/head.php'); ?>

<?= $content; ?>

<h1 class="text-center display-4">GESTION DES PRODUITS</h1>";

<!--Je modifie l'affichage du titre h3 afin d'afficher Modification si on clic sur modifier et Ajout par défaut a faire pour la partie 4-------->

<?php if (isset($_GET['action']) && $_GET['action'] == 'modification') : ?>
    <h4 class="text-center display-4  text-warning">Modification un produit</h4>
<?php else : ?>
    <h4 class="text-center display-4">Ajouter un produit</h4>
<?php endif ?>


<form method="post" action="" enctype="multipart/form-data">
    <!--- Je récupère l'id du produit que je veux modifier dans un input hidden-------->
    <input type="hidden" name="id_produit" value="<?= $id_produit ?>">

    <!----Pour tous les inputs je vais pré-remplir l'attribut value avec le resultat issu de ma condtion ternaire------->
    <label for="reference">Reference</label>
    <input type="text" name="reference" placeholder="reference du produit" id="reference" class="form-control" value="<?= $reference ?>"><br>

    <label for="categorie">Categorie</label>
    <input type="text" name="categorie" placeholder="categorie du produit" id="categorie" class="form-control" value="<?= $categorie ?>"><br>

    <label for="titre">Titre</label>
    <input type="text" name="titre" placeholder="titre du produit" id="titre" class="form-control" value="<?= $titre ?>"><br>

    <label for="description">Description</label>
    <textarea name="description" placeholder="description du produit" id="description" class="form-control"><?= $description ?></textarea><br>

    <label for="couleur">Couleur</label>
    <select name="couleur" placeholder="couleur du produit" id="couleur" class="form-control">
        <!------Si la couleur est == bleu echo bleu etc...---------->
        <option <?php if ($couleur == 'Blanc') echo 'selected'; ?>>Blanc</option>
        <option <?php if ($couleur == 'Noir') echo 'selected'; ?>>Noir</option>
        <option <?php if ($couleur == 'Rouge') echo 'selected'; ?>>Rouge</option>
        <option <?php if ($couleur == 'Bleu') echo 'selected'; ?>>Bleu</option>
        <option <?php if ($couleur == 'Jaune') echo 'selected'; ?>>Jaune</option>
        <option <?php if ($couleur == 'Multicolore') echo 'selected'; ?>>Multicolore</option>
    </select>
    <br>

    <label for="taille">Taille</label>
    <select name="taille" placeholder="taille du produit" id="taille" class="form-control">
        <!------Si la taille est == XS echo bleu etc...---------->
        <option <?php if ($taille == 'XS') echo 'selected'; ?>>XS</option>
        <option <?php if ($taille == 'S') echo 'selected'; ?>>S</option>
        <option <?php if ($taille == 'M') echo 'selected'; ?>>M</option>
        <option <?php if ($taille == 'L') echo 'selected'; ?>>L</option>
        <option <?php if ($taille == 'XL') echo 'selected'; ?>>XL</option>
        <option <?php if ($taille == 'XXL') echo 'selected'; ?>>XXL</option>
        <option <?php if ($taille == 'Autre taille') echo 'selected'; ?>>Autre taille</option>
    </select>
    <br>

    <label for="public">public</label>
    <select name="public" placeholder="public du produit" id="public" class="form-control">
        <option value="m" <?php if ($public == 'm') echo 'selected'; ?>>Homme</option>
        <option value="f" <?php if ($public == 'f') echo 'selected'; ?>>Femme</option>
        <option value="Mixte" <?php if ($public == 'Mixte') echo 'selected'; ?>>Mixte</option>
    </select>
    <br>

    <label for="photo">Photo</label>
    
    <input type="file" name="photo" id="photo" class="form-control" value="<?= $photo ?>">
    <!----Si la photo n'est pas vide (le cas ou le produit a déjà une photo----->
    <?php if (!empty($photo)) : ?>
        <p>Vous pouvez ajouter une nouvelle photo.<br>
            <!----afficher la photo---->
            <img src="<?= $photo ?>" width="50">
        </p><br>

    <?php endif;     ?>
    <input type="hidden" name="photo_actuelle" value="<?= $photo  ?>"><br>

    <br>

    <label for="prix">Prix</label>
    <input type="text" name="prix" placeholder="prix du produit" id="prix" class="form-control" value="<?= $prix ?>"><br>

    <label for="stock">Stock</label>
    <input type="text" name="stock" placeholder="stock du produit" id="stock" class="form-control" value="<?= $stock ?>"><br>



    <div class="text-center mb-5">
        <?php if (isset($_GET['action']) && $_GET['action'] == 'modification') : ?>
            <br><input type="submit" value="Valider la modification" class="btn btn-lg btn-info">
        <?php else : ?>
            <br><input type="submit" value="Ajouter le produit" class="btn btn-lg btn-primary">
        <?php endif ?>
    </div>


</form>



<?php require_once('inc/foot.php'); ?>