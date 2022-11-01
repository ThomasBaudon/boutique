<?php
    require_once('../inc/init.php');

    if(!userIsAdmin()){
        header('location:index.php');
    }
    

    $error = '';
    $res = $pdo->query("SELECT * FROM produit");
    $count = $res->rowCount();
    // var_dump($res);

    // =============================================>>>> traitement infos

    if(!empty($_POST) && empty($error)){

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
        foreach($_POST as $key=>$valeur){
            $_POST[$key] = htmlspecialchars(addslashes($valeur));
        }
        

        if(isset($_GET['action']) && $_GET['action'] == 'modification'){
            $img_bdd = $_POST['photo_actuelle'];
        }

        // >>>>>>>>>>>>>> addslashes — Ajoute des antislashs dans une chaîne 
        $categorie = addslashes($categorie);
        if(strlen($categorie) <3 || strlen($categorie) > 20) {
            $error .= '<div class="alert alert-danger">La catégorie doit contenir entre 3 et 20 caractères</div>';
        }

        // >>>>>>>>>>>>>>> REGEX
        // $regExPattern = '#^[a-zA-Z0-9._-]+$#';
        // if(!preg_match($regExPattern, $titre)) {
        //     $error .= '<div class="alert alert-danger">Caractères autorisés dans le titre : a-z A-Z 0-9 . _ -</div>';
        // }

        // Vérification de la disponibilité de la référence dans la BDD
        $r = $pdo->query("SELECT * FROM produit WHERE reference = '$reference'");
        if($r->rowCount() >= 1) {
            $error .= '<div class="alert alert-danger">Cette référence est déjà enregistrée </div>';
        }

        if (!empty($_FILES['photo'])) {// $_FILES est une superglobale qui permet de manipulier les fichiers
        
            $nomImg = time() . '_'. $_FILES['photo']['name'];// Nous allons renommer notre image afin de ne pas avoir des images de même nom

            $img_bdd = URL . "images/$nomImg"; // Je finalise le chemin d'accès dans la bdd
        

            $img_doc = BASE . "images/$nomImg";// Je finalise le chemin de l'image sur le serveur
            

            if($_FILES['photo']['size'] <= 8000000)// Si la taille est inférieur à 8Mo
            {
        
                $info = pathinfo($_FILES['photo']['name']); //pathinfo() donne des infos sur un chemin

                $ext = $info['extension']; // Je récupère l'extension de mon image dans une variable
        
                $tabExt =['jpg','png','jpeg','gif','JPG','PNG','JPEG','GIF','Jpg','Png','Jpeg','Gif'];// tableau contenant la liste des extensions que j'autorise
        
                if
                (in_array($ext,$tabExt)){ // in_array() vérifie si une valeur est dans un tableau
                    copy($_FILES['photo']['tmp_name'], $img_doc);
                    $content .= '<div class="alert alert-success">Votre produit a bien été ajouté !</div>';
                }
                else
                {
                    $error .= '<div class="alert alert-danger">Format non autorisé</div>';
                }
        
            }
            else
            {
                $error .= '<div class="alert alert-danger">Vérifier la taille de votre image</div>';
            }        
        
        }

        // ================>>>>>>>>> INSERT INTO BDD // UPDATE BDD
        if(isset($_GET['action']) && $_GET['action'] == 'modification')
        {
            $pdo->query("UPDATE produit SET reference='$_POST[reference]',categorie='$_POST[categorie]',titre='$_POST[titre]',description='$_POST[description]', couleur='$_POST[couleur]', taille='$_POST[taille]', genre='$_POST[genre]', photo='$img_bdd', prix='$_POST[prix]', stock='$_POST[stock]' WHERE id_produit = '$_GET[id_produit]'");
        }
        else
        {

            if (empty($img_bdd)) {// Si $img_bdd est vide alors il n'y a pas d'image

                $img_bdd = 'http://localhost/boutique/images/vetements.png'; // Ceci est une image par défaut que j'ai ajouté dans ma bdd
    
                $content .= '<div class="alert alert-info" role="alert text-center">Vous avez une image par défaut pour le produit</div>';
            }

            $pdo->query("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES ('$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[genre]', '$img_bdd', '$_POST[prix]', '$_POST[stock]' )");
        }

    }

    if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
        $pdo->query("DELETE FROM produit WHERE id_produit = '$_GET[id_produit]'");
        header('location:ajout_produit.php');
    }
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $test = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]'");
        $data = $test->fetch(PDO::FETCH_ASSOC);
    }
  
      $id_produit = (isset($data['id_produit'])) ? $data['id_produit'] : " " ;
      $ref = (isset($data['reference'])) ? $data['reference'] : " ";
      $cat = (isset($data['categorie'])) ? $data['categorie'] : " ";
      $title = (isset($data['titre'])) ? $data['titre'] : " ";
      $description = (isset($data['description'])) ? $data['description'] : " ";
      $color = (isset($data['couleur'])) ? $data['couleur'] : " ";
      $size = (isset($data['taille'])) ? $data['taille'] : " ";
      $gender = (isset($data['genre'])) ? $data['genre'] : " ";
      $photo = (isset($data['photo'])) ? $data['photo'] : " ";
      $prix = (isset($data['prix'])) ? $data['prix'] : " ";
      $stock = (isset($data['stock'])) ? $data['stock'] : " ";

?>

<?php 
    require_once('../inc/header.inc.php');
    require_once('inc/header-admin.php');
?>


<h1  class="text-center">Liste des produits</h1>

  <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-10">

        

          <?php 

          echo "<h2 class=\" text-center mt-3 mb-5\">".$count." produits enregistrés</h2>";
          
          echo "<table class=\"table table-striped\"><tr>";

          for($i=0; $i < $res->columnCount(); $i++)
          {
              $column = $res->getColumnMeta($i);
              echo "<th>".$column['name']."</th>";
          }
          echo "<th>Modification</th>";
          echo "<th>Suppression</th>";
          echo "</tr>";
      
             while ($produit = $res->fetch(PDO::FETCH_ASSOC))
             {
              
              echo "<tr>";
              
              // var_dump($produit);
                  foreach($produit as $key=>$value)
                  {
                    if($key == "photo"){
                      // var_dump($key);
                      echo "<td class='align-middle'><img src='http://".$value."' width=\"50\"alt='img_bdd'></td>";
                    }
                    elseif($key == "description"){
                      echo "<td class='align-middle text-truncate' style=\"max-width: 150px;\">".$value."</td>";
                    }
                    else{
                      echo "<td class='align-middle'>".$value."</td>";
                    }
                      
                  }
                  echo "<td class=\"align-top text-center align-middle\">
                          <a href=\"?action=modification&id_produit=$produit[id_produit]\">
                            <i class=\"bi bi-pen\"></i>
                          </a>
                        </td>";
                  echo "<td class=\"align-top text-center align-middle\">
                          <a href=\"?action=suppression&id_produit=$produit[id_produit]\">
                            <i class=\"bi bi-trash\"></i>
                          </a>
                        </td>";
      
              echo "</tr>";
             } 
      
          
      
      echo "</table>";
          
          ?>
        </div>
    </div>
  </div>


<!-- GESTION DES PRODUITS -->
<hr>
<hr>
<hr>

<h2 class="text-center">Gestion produit</h2>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">
        <?php echo $error;?>
        <?php echo $content;?>
            <form method="POST" action="" enctype="multipart/form-data">
                <!--id produit  -->
                <div class="mb-3">
                   
                    <input type="hidden" class="form-control" id="id_produit" name="id_produit" value="<?php echo $id_produit; ?>">
                </div>
                <!--reference  -->
                <div class="mb-3">
                    <label for="reference" class="form-label">Référence produit</label>
                    <input type="text" class="form-control" id="reference" name="reference" value="<?php echo $ref; ?>">
                </div>

                <!--categorie  -->
                <div class="mb-3">
                    <label for="categorie" class="form-label">Catégorie produit</label>
                    <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo $cat ?>">
                </div>

                <!--titre  -->
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre produit</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $title ?>">
                </div>

                <!--description  -->
                <div class="input-group mt-3 mb-3">
                    <span class="input-group-text">Description produit</span>
                    <textarea class="form-control" aria-label="With textarea" id="description" name="description"><?php echo $description ?></textarea>
                </div>

                <!--couleur  -->
                <div class="mt-3 mb-3">
                    <label for="couleur" class="form-label">Couleur du produit</label>
                    <input type="text" class="form-control" id="couleur" name="couleur" value="<?php echo $color ?>">
                </div>

                <!-- Taille -->
                <label for="taille" class="form-label mt-3 mb-3">Taille du produit</label>
                <select class="form-select" aria-label="Default select example" name="taille">
                    <option selected>Choisir la taille</option>
                    <option value="small" <?php if($size == 'small'){echo "selected";}?> >S</option>
                    <option value="medium" <?php if($size == 'medium'){echo "selected";}?>>M</option>
                    <option value="large" <?php if($size == 'large'){echo "selected";}?>>L</option>
                    <option value="X-large" <?php if($size == 'X-large'){echo "selected";}?>>XL</option>
                    <option value="XX-large" <?php if($size == 'XX-large'){echo "selected";}?>>XXL</option>
                </select>

                <!-- Genre -->
                <label for="genre" class="form-label mt-3">Public du produit</label>
                <select class="form-select mb-3" aria-label="Default select example" name="genre" id="genre">
                    <option selected>Choisir le genre</option>
                    <option value="m" <?php if($gender == 'm'){echo "selected";}?>>homme</option>
                    <option value="f" <?php if($gender == 'f'){echo "selected";}?>>femme</option>
                    <option value="mixte" <?php if($gender == 'mixte'){echo "selected";}?>>mixte</option>
                </select>

                <label for="photo">Photo</label>
    
                <input type="file" name="photo" id="photo" class="form-control" value="<?= $photo ?>">
                <!-- <?php var_dump($photo); ?> -->
                <!----Si la photo n'est pas vide (le cas ou le produit a déjà une photo----->
                <?php if (!empty($photo)) : ?>
                    <p>Vous pouvez ajouter une nouvelle photo.<br>
                        <!----afficher la photo---->
                        <img src="http://<?= $photo ?>" width="50">
                    </p><br>

                <?php endif;     ?>
                <input type="hidden" name="photo_actuelle" value="<?= $photo  ?>"><br>
                <!-- stock / prix -->

                <div class="row mt=3 mb-3">
                    <div class="col">
                        <label for="stock" class="form-label">stock</label>
                        <input type="text" class="form-control" placeholder="stock" aria-label="stock" name="stock" id="stock" value="<?php echo $stock; ?>">
                    </div>
                    <div class="col">
                        <label for="prix" class="form-label">prix</label>
                        <input type="text" class="form-control" placeholder="prix" aria-label="prix" name="prix" id="prix" value="<?php echo $prix; ?>">
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