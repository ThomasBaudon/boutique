<?php
    require_once('../inc/init.php');

    if(!userIsAdmin()){
        header('location:index.php');
    }



    $req = $pdo->query("SELECT * FROM membre WHERE statut = 0 OR statut = 2");
    $count = $req->rowCount();

    if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
        $pdo->query("DELETE FROM membre WHERE id_membre = '$_GET[id_membre]'");
        header('location:gestion-membres.php');
    }

    if(!empty($_POST)){


        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $civilite = $_POST['civilite'];
        $ville = $_POST['ville'];
        $code_postal = $_POST['code_postal'];
        $adresse = $_POST['adresse'];
        $statut = $_POST['statut'];


        if(isset($_GET['action']) && $_GET['action'] == 'modification'){
            $pdo->query("UPDATE membre SET pseudo='$_POST[pseudo]',
                                    nom='$_POST[nom]',
                                    prenom='$_POST[prenom]',
                                    email='$_POST[email]',
                                    civilite='$_POST[civilite]',
                                    ville='$_POST[ville]',
                                    code_postal='$_POST[code_postal]',
                                    adresse='$_POST[adresse]',
                                    statut='$_POST[statut]' WHERE id_membre = '$_GET[id_membre]'");
        }
        header("location:gestion-profil.php");
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
                        <th scope="col">Civilité</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">e-mail</th>
                        <th scope="col">adresse</th>
                        <th scope="col">statut</th>
                        <th scope="col">Modification</th>
                        <th scope="col">Supression</th>
                       
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                        
        <?php 

        while ($membre = $req->fetch(PDO::FETCH_ASSOC)){
                echo "
                <tr>
                    <td class=\" text-center align-middle\">" .$membre['pseudo']. "</td>";

                echo "<td class=\" text-center align-middle\">";
                switch ($membre['civilite']) {
                    case 'm':
                        echo "Mr";
                        break;
                    case 'f':
                        echo "Mme";
                        break;
                    case 'else':
                        echo "Non genré";
                        break;
                };
                
                echo "</td>";




                echo "<td class=\" text-center align-middle\">" . $membre['nom']. "</td>
                    <td class=\" text-center align-middle\">" . $membre['prenom']. "</td>
                    <td class=\" text-center align-middle\">" . $membre['email']. "</td>
                    <td class=\" align-middle\">" . $membre['adresse']." <br> ". $membre['code_postal']." <br> ". $membre['ville']."</td>";


                    echo "<td class=\" text-center align-middle\">";
                        switch ($membre['statut']) {
                            case '1':
                                echo "Admin";
                                break;
                            case '0':
                                echo "Membre";
                                break;
                            case '2':
                                echo "Ban";
                                break;
                        };

                echo "<td class=\"align-top text-center align-middle\">
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10">

        <?php 
            if(isset($_GET['action']) && $_GET['action'] == 'modification'){
                $reqProfil = $pdo->query("SELECT * FROM membre WHERE id_membre = $_GET[id_membre]");
                $detailProfil = $reqProfil->fetch(PDO::FETCH_ASSOC);
                ?>

            <form method="POST" action="">

            
                    <!-- PSEUDO -->
            <div class="mb-3">
                <label for="pseudo" class="form-label">Votre pseudo</label>
                <input type="text" class="form-control pseudo" placeholder="pseudo" aria-label="pseudo" id="pseudo" name="pseudo" value="<?php echo $detailProfil['pseudo']; ?>">
            </div>

            <!-- Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Choisissez un nom</label>
                <input type="text" class="form-control nom" placeholder="nom" aria-label="nom" id="nom" name="nom" value="<?php echo $detailProfil['nom']; ?>">
            </div>

            <!-- Prenom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Choisissez un prénom</label>
                <input type="text" class="form-control prenom" placeholder="prénom" aria-label="prenom" id="prenom" name="prenom" value="<?php echo $detailProfil['prenom']; ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Votre e-mail</label>
                <input type="email" class="form-control" id="email" aria-describedby="email" name="email" value="<?php echo $detailProfil['email']; ?>">
            </div>

            <!-- GENRE -->
            <div class="input-group mb-3">
                <label class="input-group-text" for="civilite">Vous êtes</label>
                <select class="form-select" id="civilite" name="civilite">
                    <option>Choix...</option>

                    <?php 
                        switch ($detailProfil['civilite']) {
                            case 'm':
                                echo "
                                <option value=\"m\" selected >Homme</option>
                                <option value=\"f\" >Femme</option>
                                ";
                                break;
                            case 'f':
                                echo "
                                <option value=\"m\" >Homme</option>
                                <option value=\"f\" selected>Femme</option>
                                ";
                                break;
                        };
                    ?>
<!-- 

                    <option value="m" <?php if ($detailProfil['civilite'] === 'm') echo 'selected'; ?>>Homme</option>
                    <option value="f" <?php if ($detailProfil['civilite'] === 'f') echo 'selected'; ?>>Femme</option> -->
                </select>
            </div>

            <!-- Adresse -->
            <div class="mb-3">
                <label for="adresse" class="form-label">Votre adresse</label>
                <input type="text" class="form-control adresse" placeholder="adresse" aria-label="adresse" id="adresse" name="adresse" value="<?php echo $detailProfil['adresse']; ?>">
            </div>

            <!-- zip -->
            <div class="mb-3">
                <label for="code_postal" class="form-label">Code postal</label>
                <input type="text" class="form-control code_postal" placeholder="Code postal" aria-label="code_postal" id="code_postal" name="code_postal" min="1" max="6" value="<?php echo $detailProfil['code_postal']; ?>">
            </div>

            <!-- ville -->
            <div class="mb-3">
                <label for="ville" class="form-label">Votre ville</label>
                <input type="text" class="form-control ville" placeholder="ville" aria-label="ville" id="ville" name="ville" value="<?php echo $detailProfil['ville']; ?>">
            </div>

            <!-- STATUT -->
            <div class="input-group mb-3">
                <label class="input-group-text" for="statut">Vous êtes</label>
                <select class="form-select" id="statut" name="statut">
                    <option selected>Choix...</option>
                    <option <?php if ($detailProfil['statut'] == 0) echo 'selected'; ?> value="0">Membre</option>
                    <option <?php if ($detailProfil['statut'] == 2) echo 'selected'; ?>value="2">Ban</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">MODIFIER</button>


        </form>

        <?php } /* else{
            header("location:gestion-profil.php");
        }  */; ?>
            
            

        </div>
    </div>
</div>


<?php
    require_once('../inc/footer.inc.php');
?>