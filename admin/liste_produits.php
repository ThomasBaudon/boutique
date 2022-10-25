<?php
  require_once('../inc/init.php');

  if(!userIsAdmin()){
    header('location:../index.php');
    exit();
  }

  $res = $pdo->query("SELECT * FROM produit");
  $count = $res->rowCount();
  // var_dump($res);

  if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
    $pdo->query("DELETE FROM produit WHERE id_produit = '$_GET[id_produit]'");
    header('location:liste_produits.php');
  }


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



<?php
  require_once('../inc/footer.inc.php');
?>