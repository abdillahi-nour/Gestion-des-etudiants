<?php
// Démarrez la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container-fluid" style="height: 10px; background-color: #303f9b;"></div>
<div class="container-fluid p-3 bg-light">
    <div class="row">
        <div class="col-md-3 text-success">
         <h3><i class="fas fa-tachometer-alt"></i> E-Project</h3>
        </div>
        <div class="col-md-6">
            <form class="form-inline my-2 my-lg-0" action="" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-primary my-1 my-sm-0" type="submit">Search</button>
            </form>
        </div>
        <div class="col-md-3">
            <?php if (!empty($_SESSION['user'])): ?>
                <a class="btn btn-success text-white"><i class="fas fa-user"></i><?php echo htmlspecialchars($_SESSION['user']['name']); ?></a>
                <a data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary text-white"> <i class="fas fa-sign-out-alt"></i> Logout</a>
           
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Deconnexion Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>Do You want to logout?</h4>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="logout.php" type="button" class="btn btn-primary text-white">Logout</a>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-custom"> 
 <a class="nav-brand" href="#"><i class="fas  fa-home"></i></a>
 <button class="navbar-toggler" type="button" data-toggle="collapse" 
  data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
  aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active"><a class="nav-link" href="gestion_etudiants.php"> <i class="fas fa-user"></i>Gestion Etudiant</a> </li>
       <li class="nav-item active"><a class="nav-link" href="localiser_les_etudiants.php"> <i class="fas fa-map-marker-alt"></i>Localisation</a> </li>
       <li class="nav-item active"><a class="nav-link" href="add_image.php"><i class="fas fa-images"></i>Gestion image</a> </li>

      
      <li class="nav-item active"> <a class="nav-link disabled" href="statistique.php">Statistiques</a></li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <a href="#" class="btn btn-light my-2 my-sm-0 " type="submit"> <i class="fas fa-cog"></i> Parametre</a>
       <a href="#" class="btn btn-light my-2 my-sm-0 ml-1" type="submit">
       <i class="fas fa-phone-square-alt"></i> Contacts</a>
    </form>
  </div>
</nav>