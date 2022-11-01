<?php
    require_once('../inc/init.php');

    if(!userIsAdmin()){
        header('location:index.php');
    }

    $req = $pdo->query("SELECT * FROM membre WHERE statut = 0");
    $count = $req->rowCount();

    if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
        $pdo->query("DELETE FROM membre WHERE id_membre = '$_GET[id_membre]'");
        header('location:gestion-membres.php');
    }

    require_once('../inc/header.inc.php');
    require_once('inc/header-admin.php');
?>

<h1 class="text-center">Gestion membres</h1>
<h3 class="text-center"> <?php echo $count; ?> membres enregistrés </h3>

<!-- afficher les membres -->
<!-- changer leur statut -->
<!-- voir le détail de leur commande -->
<!-- exporter en PDF ? -->


<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">

        <?php echo "$content"; ?>

            <!-- TABLE -->
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Pseudo</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">e-mail</th>
                        <th scope="col">adresse</th>
                        <th scope="col" class="text-end">Commande(s)</th>
                        <th scope="col">Modification</th>
                        <th scope="col">Supression</th>
                       
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                        
                    <?php 

                            while ($membre = $req->fetch(PDO::FETCH_ASSOC)){
                            echo "
                            <tr>
                                <td class=\" text-center align-middle\">" . $membre['pseudo']. "</td>
                                <td class=\" text-center align-middle\">" . $membre['nom']. "</td>
                                <td class=\" text-center align-middle\">" . $membre['prenom']. "</td>
                                <td class=\" text-center align-middle\">" . $membre['email']. "</td>
                                <td class=\" align-middle\">" . $membre['adresse']." <br> ". $membre['code_postal']." <br> ". $membre['ville']."</td>
                                <td class=\"text-center align-middle\">
                                    <a href=\"?action=afficheCommande&id_membre=$membre[id_membre]\">
                                        commandes
                                    </a>
                                </td>

                                <td class=\"align-top text-center align-middle\">
                                    <a href=\"?action=modification&id_membre=$membre[id_membre]\">
                                        <i class=\"bi bi-pen\"></i>
                                    </a>
                                </td>

                                <td class=\"align-top text-center align-middle\">
                                    <a href=\"?action=suppression&id_membre=$membre[id_membre]\">
                                        <i class=\"bi bi-trash\"></i>
                                    </a>
                                </td>

                            </tr>
                            ";
                        }
                    
                    ?>

                </tbody>
            </table>

            </div>
        </div>
    </div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-10">

        <h2 class="text-center">Détails des commandes</h2>

            <?php 
                 if(isset($_GET['action']) && $_GET['action'] == 'afficheCommande'){
                    $reqCommande = $pdo->query("SELECT DISTINCT * FROM details_commande
                                                INNER JOIN commande ON details_commande.id_commande  = commande.id_commande
                                                INNER JOIN membre ON commande.id_membre  = membre.id_membre
                                                INNER JOIN produit ON  details_commande.id_produit  = produit.id_produit
                                                WHERE commande.id_membre = $_GET[id_membre]");
                    $detailCommande =  $reqCommande->fetch(PDO::FETCH_ASSOC);

                    $commandes = $pdo->query("SELECT*FROM details_commande");



                    // $qttProduit = $pdo-> query("SELECT * FROM details_commande WHERE quantite = '".$_GET['id_produit']."' ");


                if($detailCommande > 0){
                echo "<table class=\"table table-hover table-bordered\">
                        <thead class=\"table-dark\">
                                <tr>
                                    <th  class=\" text-center align-middle\" scope=\"col\">commande</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\">membre</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\">détail commande</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\">quantité</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\">prix unitaire</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\" class=\"text-end\">montant total</th>
                                    <th  class=\" text-center align-middle\" scope=\"col\" class=\"text-end\">détail</th>
                                </tr>
                            </thead>";

                
                    while ($detailCommande = $reqCommande->fetch(PDO::FETCH_ASSOC)){
                    
                    
                    echo "<tbody class=\"table-group-divider\">";
                        echo" <tr>";

                            /* NUM COMMANDE */
                            // foreach($detailCommande as $key =>$valeur){
                                echo "<td class=\" text-center align-middle\">N° ".$detailCommande['id_commande']."</td>";
                            // }
                            
        
                            /* NOM PRENOM */
                            echo "<td class=\" text-center align-middle\" colrow=\"5\">". $detailCommande['nom']." ". $detailCommande['prenom']."</td>";
                                
                            /* TITRE */
                            echo "<td class=\" text-center align-middle\">
                                    <table width=\"100%\">
                                        <tr>
                                            <td class=\" align-middle\">" .$detailCommande['titre']. "</td>
                                        </tr>
                                    </table>
                                </td>";
        
                            /* QUANTITE */
                            echo "<td class=\" text-center align-middle\">                           
                                    <table class=\"bordered\" width=\"100%\">
                                        <tr>
                                            <td class=\" text-center align-middle\">" .$detailCommande['quantite']. "</td>
                                        </tr>
                                    </table>
                                </td>";
        
                            /* PRIX */
                            echo "<td class=\" text-center align-middle\">
                                    <table width=\"100%\">
                                        <tr>
                                            <td class=\" text-center align-middle bordered\">" .$detailCommande['prix']. "€</td>
                                        </tr>
                                    </table>
                                </td>";
                                    
                            /* MONTANT */
                            echo "<td class=\" text-center align-middle\">".$detailCommande['montant']. "€</td>";
                            echo "<td class=\" text-center align-middle\"><a href=\"#\">Détail</a></td>";
        
                        echo "</tr>";
                    echo "</tbody>";        
                    };



                echo "</table>";

                echo "<table class=\"table table-hover table-bordered\">";
                echo "<tbody class=\"table-group-divider\">";                        
                echo "</tbody>";
                echo "</table>";
                }else{
                    echo"<p class=\"text-center\">Aucune commande n'a été effectué de la part de ce membre </p>";
                }

                

            }else{
                echo"<p class=\"text-center\">Aucune commande à afficher</p>";
            }
        ?>
        </div>
        
    </div>
</div>

<?php
    require_once('../inc/footer.inc.php');
?>