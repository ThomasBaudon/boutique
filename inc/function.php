<?php 

function userConnected(){
    if(isset($_SESSION['membre']))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function userIsAdmin(){
    if(userConnected() && $_SESSION['membre']['statut'] ==1){
        return true;
    }else{
        return false;
    }
}

function creation_panier(){
    if(!isset($_SESSION['panier'])){
        $_SESSION['panier']['id_produit']= [];
        $_SESSION['panier']['titre']= [];
        $_SESSION['panier']['prix']= [];
        $_SESSION['panier']['quantite']= [];
    } else {
        
        // $id_produit = $_SESSION['panier']['id_produit'];
        // $titre_produit = $_SESSION['panier']['titre'];
        // $prix_produit = $_SESSION['panier']['prix'];
        // $quantite = $_SESSION['panier']['quantite'];

        return "Hello le panier";
    }
    return true;
}

function ajouterArticle($id_produit, $quantite, $prix_produit){

        creation_panier();

        //Si le produit existe déjà on ajoute seulement la quantité
        $positionProduit = array_search($id_produit,  $_SESSION['panier']['id_produit']);

        if ($positionProduit == true)
        {
            // var_dump($positionProduit);
           $_SESSION['panier']['quantite'][$positionProduit] += $quantite ;
        }
        else
        {
           //Sinon on ajoute le produit
           array_push( $_SESSION['panier']['id_produit'],$id_produit);
           array_push( $_SESSION['panier']['quantite'],$quantite);
           array_push( $_SESSION['panier']['prix'],$prix_produit);
        }

}


function MontantGlobal(){
    $total=0;
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
       $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return $total;
 }


?>