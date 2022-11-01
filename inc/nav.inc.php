<?php require_once 'init.php'; ?>

<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= URL?>index.php"">SHOP Logo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">

        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>index.php">Accueil</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>index.php">Boutique</a>
        </li>




      <?php if(userIsAdmin()):?>
        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>admin/index.php"profil.php">Dashboard</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="<?php URL?>profil.php"profil.php">Profil</a>
        </li> -->
      <?php endif ?>

      <?php if(userConnected()):?>
        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>profil.php"profil.php">Profil</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>connexion.php?action=deconnexion">Déconnexion</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php URL?>panier.php">
              Panier <i class="bi bi-cart2"></i>
            </a>
        </li>


        <?php else: ?>

        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>inscription.php">Inscription</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php URL?>connexion.php">Connexion</a>
        </li>

      <?php endif ?>

       
      </ul>

    </div>
  </div>
</nav>