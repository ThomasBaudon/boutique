<?php
require_once('./inc/init.php');


if(isset($_POST['ajoute_panier'])){
    $req = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
    $produit = $req->fetch(PDO::FETCH_ASSOC);

    // var_dump($produit);

    $id_produit = $produit['id_produit'];
    $quantite = $_POST['quantite'];
    $reference = $produit['reference'];
    $prix = $produit['prix'];
    $titre = $produit['titre'];

    ajoutProduit($id_produit, $quantite, $prix, $titre, $reference);
    // var_dump($_SESSION['panier']);
}else{
    $content = "Votre panier est vide !";
}


require_once('./inc/header.inc.php');
require_once('./inc/nav.inc.php');
?>


<h1 class="text-center"> Panier</h1>

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
            <?php 
                if(empty($_SESSION['panier']['id_produit'])){
                    echo "
                    <tr>
                        <td colspan='5' class='text-center'>
                            Votre panier est vide
                        </td>
                    </tr>
                    ";
                }else{
                    for($i=0; $i<count($_SESSION['panier']['id_produit']); $i++){
                        echo "
                        <tr>
                            <td>" . $_SESSION['panier']['id_produit'][$i]. "</td>
                            <td>" . $_SESSION['panier']['titre'][$i]. "</td>
                            <td>" . $_SESSION['panier']['reference'][$i]. "</td>
                            <td>" . $_SESSION['panier']['quantite'][$i]. "</td>
                            <td class=\"text-end\">" . $_SESSION['panier']['prix'][$i]. "€</td>

                        </tr>
                        ";
                    }

                    echo "<th colspan=\"5\" class=\"text-end\">Montant total :".montantTotal()."€</th>";

                    if(!userConnected()){
                        echo "<div class=\"alert alert-light\" role=\"alert\"> Veuillez vous connecter ou vous inscrire</div>";
                    }else{
                        echo "
                        <form action=\"\" method=\"POST\">
                            <div class=\"alert alert-light\" role=\"alert\">
                                <tr>
                                    <td colspan=\"5\">
                                        <input type=\"submit\" class=\"btn btn-success\" name=\"payer\" value=\"Valider le panier\">
                                    </td>
                                </tr>
                            </div>
                        </form>
                        
                        ";
                    }
                }

            
            ?>

           



            <!-- <tr>
                <td>

                </td>
            </tr>

                <tr>
                    <td>id_produit</td>
                    <td>Titre</td>
                    <td>Référence</td>
                    <td>quantité</td>
                    <td class="text-end">0000€</td>
                </tr> -->

                <!-- <tr>
                    <td><?php echo $id_produit; ?></td>
                    <td><?php echo $titre; ?></td>
                    <td><?php echo $reference; ?></td>
                    <td><?php echo $quantite; ?></td>
                    <td class="text-end"><?php echo $prix; ?>€</td>
                </tr> -->


                </tbody>
            </table>
        </div>
    </div>
 </div>





<?php  require_once('./inc/footer.inc.php'); ?>