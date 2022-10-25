<?php
  require_once('./inc/init.php');

  // all produits
  $req = $pdo->query("SELECT * FROM produit");

  // categories
  $reqCategories = $pdo->query("SELECT DISTINCT categorie FROM produit");
  // $cat = $reqCategories->fetch(PDO::FETCH_ASSOC);

  require_once('./inc/header.inc.php');
  require_once('./inc/nav.inc.php');
?>

<h1 class="m-3  text-center">Projet boutique</h1>

<div class="container shopPage" >
  <div class="row justify-content-center d=flex">



    <!-- catégories -->
    <div class="col-3">
      <h2>Catégories</h2>
      <ul class="list-group">
        <?php while($cat = $reqCategories->fetch(PDO::FETCH_ASSOC)){
            echo "
            <li class=\"list-group-item\">
              <a href=\"?action=affichage&categorie=$cat[categorie]\" class=\"stretched-link\">$cat[categorie]</a>
            </li>
            ";
        } ?>
        
      </ul>
    </div>

    <!-- produits -->
    <div class="col-7">
      <h2>Produits</h2>
        <div class="row">
          <div class="col-12 d-flex flex-wrap">

          

<?php 
          
          if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
              $onceCat = $pdo->query("SELECT * FROM produit WHERE categorie = '$_GET[categorie]'");
              while($all = $onceCat->fetch(PDO::FETCH_ASSOC)){
                echo "

                <div class=\"card\" style=\"width: 30%; margin-bottom: 1rem; margin-right: 1rem;\">
                  <a href=\"produit.php?action=pageproduit&id_produit=$all[id_produit]\" style=\"cursor: pointer;\">  
                    <img src=\"http://$all[photo]\" class=\"card-img-top\" alt=\"...\">
      
                    <div class=\"card-body\">
                      <h5 class=\"card-title\">$all[titre]</h5>
                      <p class=\"card-text text-truncate \">$all[description]</p>
                    </div>
      
                    <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item\">$all[categorie]</li>
                      <li class=\"list-group-item text-end fs-5\"><strong>$all[prix] €</strong></li>
                    </ul>
      
                  </a>  
                </div>
                
              ";
              }

          } else{

            while($all = $req->fetch(PDO::FETCH_ASSOC)){
              // print_r($all);
  
              echo "
                <div class=\"card\" style=\"width: 30%; margin-bottom: 1rem; margin-right: 1rem;\">
                <a href=\"produit.php?action=pageproduit&id_produit=$all[id_produit]\" style=\"cursor: pointer;\">  
                <img src=\"http://$all[photo]\" class=\"card-img-top\" alt=\"...\">
  
                <div class=\"card-body\">
                      <h5 class=\"card-title\">$all[titre]</h5>
                      <p class=\"card-text text-truncate\">$all[description]</p>
                    </div>
  
                    <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item\">$all[categorie]</li>
                      <li class=\"list-group-item text-end fs-5\"><strong>$all[prix] €</strong></li>
                    </ul>
                </a>
                </div>
                
              ";
               
            }
          }

?>



          </div>
        </div>
    </div>
  </div>
</div>

<?php
  require_once('./inc/footer.inc.php');
?>