<?php
require_once('./inc/init.php');


    if(isset($_POST)){
        $r = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
        $produit = $r->fetch(PDO::FETCH_ASSOC);

        $id_produit = $produit['id_produit'];
        $prix_produit = $produit['prix'];
        $titre = $produit['titre'];
        $reference = $produit['reference'];
        $quantite = $_POST['quantite'];

        ajouterArticle($id_produit, $_POST['quantite'], $prix_produit);
    }else{
        $content = "Votre panier est vide !";
    }


require_once('./inc/header.inc.php');
require_once('./inc/nav.inc.php');
?>

 <!-- titre -->
 <h1 class="mb-3 text-center"> Page panier </h1>
 <?php echo $content; ?>

 <div class="container">
    <div class="row justify-content-center">
        <div class="col-10">

            <!-- TABLE -->

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Référence</th>
                        <th scope="col">quantité</th>
                        <th scope="col" class="text-end">Prix</th>
                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td><?php echo $id_produit; ?></td>
                    <td><?php echo $titre; ?></td>
                    <td><?php echo $reference; ?></td>
                    <td><?php echo $quantite; ?></td>
                    <td class="text-end"><?php echo $prix_produit; ?>€</td>
                </tr>

                    <!-- ESSAI CODE INUTE -->
                    <!-- <?php 
                        $nbArticles=count($_SESSION['panier']['id_produit']);
                        var_dump($_SESSION['panier']);
                           if ($nbArticles <= 0){
                               echo "<tr><td>Votre panier est vide </ td></tr>";
                           }
                           else
                           {
                              for ($i=0 ;$i < $nbArticles ; $i++)
                              {

                                echo "
                                <tr>
                                <td>$id_produit<td>
                                <td>$titre<td>
                                <td>$reference</td>
                                <td>$quantite</td>
                                <td class=\"text-end\">$prix_produit €</td>
                                </tr>
                                
                                ";
                            }
                        }
                        

                    ?> -->

                </tbody>
            </table>
        </div>
    </div>
 </div>

<?php  require_once('./inc/footer.inc.php'); ?>