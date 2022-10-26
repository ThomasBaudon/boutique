<?php
require_once('./inc/init.php');

if(isset($_POST['ajoute_panier'])){
    $req = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
    $produit = $req->fetch(PDO::FETCH_ASSOC);



    $id_produit = $produit['id_produit'];
    $quantite = $_POST['quantite'];
    $reference = $produit['reference'];
    $prix = $produit['prix'];
    $titre = $produit['titre'];

    ajoutProduit($id_produit, $quantite, $prix, $titre, $reference);

}else{
    $content = "Votre panier est vide !";
}


if(isset($_POST['payer'])){
    for($i=0; $i < count($_SESSION['panier']['id_produit']);$i++){
        // je fais une requête pour récupérer les datas des produits qui sont dans ma session
        $r = $pdo->query("SELECT * FROM produit WHERE id_produit = '".$_SESSION['panier']['id_produit'][$i]."' ");

        $data = $r->fetch(PDO::FETCH_ASSOC);

        // var_dump($data['stock']);

        // Si la quantité est inférieure à ce que j'ai en stock, alors on aura 2 cas possible :
        if($data['stock'] < $_SESSION['panier']['quantite'][$i]){

            if($data['stock']>0){// Si la quantité disponible est supérieure à 0 mais inférieure à ce que l'user demande

                $_SESSION['panier']['quantite'][$i] = $data['stock'];

            }else{// Sinon le produit n'est plus disponible

                $content = "Le produit demandé n'est plus en stock";
                retirerProduit($_SESSION['panier']['id_produit'][$i]);

                $i--;// Je refais un tour de panier afin de m'assurer que tout est ok avant la validation
            }

            // je déclare une variable s'il y'a un problème sur le stock
            $error = true;
        }
    }   

    // S'il n'y a pas de problème sur le stock
    if(!isset($error)){

        $pdo->query("INSERT INTO commande(id_membre, montant, date_enregistrement, etat) VALUES ('".$_SESSION['membre']['id_membre']."','".montantTotal()."', NOW(), 'en cours de traitement' ) ");


        $id_commande = $pdo->lastInsertId();

        for($i=0; $i < count($_SESSION['panier']['id_produit']); $i++){
            $pdo->query("INSERT INTO details_commande(id_commande, id_produit, quantite, prix) VALUES ('$id_commande', '".$_SESSION['panier']['id_produit'][$i]."', '".$_SESSION['panier']['quantite'][$i]."', '".$_SESSION['panier']['prix'][$i]."')");


            // mettre à jour le panier
            $pdo->query("UPDATE produit SET stock = stock - '".$_SESSION['panier']['quantite'][$i]."' WHERE id_produit = '".$_SESSION['panier']['id_produit'][$i]."' ");

        }

        unset($_SESSION['panier']);

    }


}



require_once('./inc/header.inc.php');
require_once('./inc/nav.inc.php');
?>


<h1 class="text-center"> Panier</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">

        <?php echo "$content"; ?>

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
                </tbody>
            </table>
        </div>
    </div>
 </div>





<?php  require_once('./inc/footer.inc.php'); ?>