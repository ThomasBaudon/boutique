<?php
    require_once('../inc/init.php');

    if(!userIsAdmin()){
        header('location:index.php');
    }
    

    $error = '';

    // =============================================>>>> traitement infos

    if(!empty($_POST)){

        // =======================================>>>>> VARIABLES

        $reference = $_POST['reference'];
        $categorie = $_POST['categorie'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $couleur = $_POST['couleur'];
        $taille = $_POST['taille'];
        $genre = $_POST['genre'];
        $photo = $_FILES['photo'];
        $stock = $_POST['stock'];
        $prix = $_POST['prix'];


        // ===============>>>>  htmlspecialchars = Convertit les caractères spéciaux en entités HTML
        foreach($_POST as $key =>$valeur){
            $_POST[$key] = htmlspecialchars(addslashes($valeur));
        }

        // >>>>>>>>>>>>>> addslashes — Ajoute des antislashs dans une chaîne 
        $categorie = addslashes($categorie);
        if(strlen($categorie) <3 || strlen($categorie) > 20) {
            $error .= '<div class="alert alert-danger">La catégorie doit contenir entre 3 et 20 caractères</div>';
        }

        // >>>>>>>>>>>>>>> REGEX
        $regExPattern = '#^[a-zA-Z0-9._-]+$#';
        if(!preg_match($regExPattern, $titre)) {
            $error .= '<div class="alert alert-danger">Caractères autorisés dans le titre : a-z A-Z 0-9 . _ -</div>';
        }

        // Vérification de la disponibilité de la référence dans la BDD
        $r = $pdo->query("SELECT * FROM produit WHERE reference = '$reference'");
        if($r->rowCount() >= 1) {
            $error .= '<div class="alert alert-danger">Cette référence est déjà enregistrée </div>';
        }

        if (!empty($_FILES['photo']) && empty($error)) {
            
            // $_FILES est une superglobale qui permet de manipulier les fichiers
        
            $nomImg = time() . '_' . rand() . '_' . $_FILES['photo']['name'];
            // Nous allons renommer notre image afin de ne pas avoir des images de même nom

        
            $img_bdd = URL . "images/$nomImg"; // Je finalise le chemin d'accès dans la bdd
        
            define("BASE", $_SERVER['DOCUMENT_ROOT'] . '/php/boutique/');
            // Je prépare le chemin de l'image dans le dossier qui est sur le serveur


            $img_doc = BASE . "images/$nomImg";
            // Je finalise le chemin de l'image sur le serveur
        
        
            // Si la taille est inférieur à 8Mo
            if($_FILES['photo']['size'] <= 8000000)
            {
        
                //pathinfo() donne des infos sur un chemin
                $info = pathinfo($_FILES['photo']['name']);
        
                $ext = $info['extension']; // Je récupère l'extension de mon image dans une variable
        
                $tabExt =['jpg','png','jpeg','gif','JPG','PNG','JPEG','GIF','Jpg','Png','Jpeg','Gif'];// tableau contenant la liste des extensions que j'autorise
        
                if(in_array($ext,$tabExt)){ // in_array() vérifie si une valeur est dans un tableau
                    copy($_FILES['photo']['tmp_name'], $img_doc);
                    $pdo->query("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, genre, photo,prix, stock) VALUES ('$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[genre]', '$img_doc', '$_POST[prix]', '$_POST[stock]' )");
                }else{
                    echo "Format non autorisé";
                }
        
            }else{
                echo "Vérifier la taille de votre image";
            }
        
        
        
        
        }


        // ================>>>>>>>>> INSERT INTO BDD
        /* if(empty($error)) {
            $pdo->query("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, genre, photo,prix, stock) VALUES ('$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[genre]', $_POST[photo]', '$_POST[prix]', '$_POST[stock]' )");
            $error .= '<div class="alert alert-success">Votre produit est bien rengistré !</div>';
        } */
    }





?>

<?php 
    require_once('../inc/header.inc.php');
    require_once('../inc/nav.inc.php');
?>

<h1 class="text-center">Gestion produit</h1>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">
        <?php echo $error;?>
        <?php echo $content;?>
            <form method="POST" action="" enctype="multipart/form-data">
                <!--reference  -->
                <div class="mb-3">
                    <label for="reference" class="form-label">Référence produit</label>
                    <input type="text" class="form-control" id="reference" name="reference">
                </div>

                <!--categorie  -->
                <div class="mb-3">
                    <label for="categorie" class="form-label">Catégorie produit</label>
                    <input type="text" class="form-control" id="categorie" name="categorie">
                </div>

                <!--titre  -->
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre produit</label>
                    <input type="text" class="form-control" id="titre" name="titre">
                </div>

                <!--description  -->
                <div class="input-group mt-3 mb-3">
                    <span class="input-group-text">Description produit</span>
                    <textarea class="form-control" aria-label="With textarea" id="description" name="description"></textarea>
                </div>

                <!--couleur  -->
                <div class="mt-3 mb-3">
                    <label for="couleur" class="form-label">Couleur du produit</label>
                    <input type="text" class="form-control" id="couleur" name="couleur">
                </div>

                <!-- Taille -->
                <label for="taille" class="form-label mt-3 mb-3">Taille du produit</label>
                <select class="form-select" aria-label="Default select example" name="taille">
                    <option selected>Choisir la taille</option>
                    <option value="small">S</option>
                    <option value="medium">M</option>
                    <option value="large">L</option>
                    <option value="X-large">XL</option>
                    <option value="XX-large">XXL</option>
                </select>

                <!-- Genre -->
                <label for="genre" class="form-label mt-3">Public du produit</label>
                <select class="form-select mb-3" aria-label="Default select example" name="genre" id="genre">
                    <option selected>Choisir le genre</option>
                    <option value="m">homme</option>
                    <option value="f">femme</option>
                    <option value="mixte">mixte</option>
                </select>

                <!-- photo -->
                <div class="input-group mt-3 mb-3">
                     <input type="file" class="form-control" aria-describedby="inputGroupFileAddon03" aria-label="photo" id="photo" name="photo">
                    <button class="btn btn-outline-secondary" type="button" id="upload">Envoyer image</button>
                </div>

                <!-- stock / prix -->

                <div class="row mt=3 mb-3">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="stock" aria-label="stock" name="stock" id="stock">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="prix" aria-label="prix" name="prix" id="prix">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">Enregistrer produit</button>
            </form>
        </div>
    </div>
</div>
   

<?php
  require_once('../inc/footer.inc.php');
?>