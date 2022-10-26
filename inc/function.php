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
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_produit']= array();
        $_SESSION['panier']['quantite']= array();
        $_SESSION['panier']['prix']= array();
        $_SESSION['panier']['titre']= array();
    }
}



function ajoutProduit($id_produit, $quantite, $prix, $titre, $reference){ //Qui prend id, qtt, prix et titre
    creation_panier();//J’execute la fonction de creation lors de l'ajout

    // Je vérifie sir le produit est déjà dans une session panier
    // array_search() Recherche dans un tableau la première clé associée à la valeur
    $position = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($position !== false){ //Si le produit est trouvé alors j'incrémente la qtt
        $_SESSION['panier']['quantite'][$position] += $quantite ;
    }else{// Sinon je l'ajoute comme un nouveau produit. [] à la fin permet d'ajouter un nouveau produit et de ne pas écraser ce qui est déjà présent dans le panier
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['reference'][] = $reference;
    }

}


function montantTotal(){
    $total = 0;

    for($i=0; $i < count($_SESSION['panier']['id_produit']); $i++){
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return $total;
}


// function MontantGlobal(){
//     $total=0;
//     for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
//     {
//        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
//     }
//     return $total;
//  }


?>