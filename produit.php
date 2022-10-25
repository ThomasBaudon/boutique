<?php
require_once('./inc/init.php');





if(isset($_GET['action']) && $_GET['action'] == 'pageproduit'){
    if($id_produit = $_GET["id_produit"]){

        $id_produit =  $_GET['id_produit'];
        $r = $pdo->query("SELECT * FROM produit WHERE id_produit = $id_produit");

        if($r->rowCount()<=0){//je vérifie que la référence existe bien dans la BDD sinon renvoie vers index.php
            header('location:index.php');
            exit();
        }else{
            $productInfos = $r->fetch(PDO::FETCH_ASSOC);
        }
    }

}

if(!empty($_POST)) {

    // Je créé une sessions que j'appelle 'PANIER' pour stocker les infos du produit
    $_SESSION['panier']['id_produit']= $productInfos['id_produit'];
    $_SESSION['panier']['titre']= $productInfos['titre'];
    $_SESSION['panier']['reference']= $productInfos['reference'];
    $_SESSION['panier']['prix']= $productInfos['prix'];
    $_SESSION['panier']['quantite']= $_POST['quantite'];


    // print_r($_SESSION['panier']);
    header('location:panier.php');
}

require_once('./inc/header.inc.php');
require_once('./inc/nav.inc.php');
?>



<div class="container">
    <?php echo $content; ?>
    <div class="row justify-content-center d=flex mt-3">
        <div class="col-5">
            <img src="http://<?php echo $productInfos['photo']; ?>" alt="image=produit" class="img-fluid">
        </div>
        <div class="col-5">
        <form action="panier.php" method="POST">

            <!-- id_produit -->
            <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>">

            <!-- titre -->
            <h1 class="mb-3"><?php echo $productInfos['titre']; ?></h1>
            <input type="hidden" name="titre" value ="<?php echo $productInfos['titre']; ?>">

            <!-- couleur -->
            <h5 class="mb-3">Prix : <?php echo $productInfos['prix']; ?>€</h5>
            <input type="hidden" name="prix" value ="<?php echo $productInfos['prix']; ?>">

            <!-- couleur -->
            <h5 class="mb-3">Couleur : <?php echo $productInfos['couleur']; ?></h5>

            <!-- couleur -->
            <h5 class="mb-3">Taille : <?php echo $productInfos['taille']; ?></h5>
            <br>
            <hr>
            <br>
            <!-- description -->
            <p class="mb-3"><?php echo $productInfos['description']; ?></p>
            <br>
            <hr>
            <br>
            
                <!-- stock -->
                <select name="quantite" id="quantite" class="mb-3" require>
                    <option selected>En stock</option>                       
                    
                        <?php for($stock = 1; $stock <= $productInfos['stock']; $stock++ ){ ?>
                            <option value="<?php echo $stock;?>">
                                <?php echo $stock;?>
                            </option> 
                        <?php } ?>
                
                </select> <br>
                <!-- <?php echo gettype($stock); ?> -->
                <!-- <?php echo $stock; ?> -->
                <!-- ACHETER -->
                <button type="submit" class="mb=3 btn btn-success">AJOUTER AU PANIER</button>
            </form>
        </div>
        <div class="col-10">
            <a href="index.php?action=affichage&categorie=<?php echo $productInfos['categorie'];?>" type="button" class="btn btn-outline-secondary w-100 mt-3 mb-5 text-uppercase" > retour à la catégorie <?php echo $productInfos['categorie'];?> </a>
        </div>
    </div>
</div>

<?php  require_once('./inc/footer.inc.php'); ?>