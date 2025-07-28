// admin.js

window.onload = function() {
    // Simuler un utilisateur connecté
    var user = { name: "Abdillahi Nur" };  // Remplace par une vraie méthode pour récupérer l'utilisateur connecté

    // Créer le contenu dynamique pour l'en-tête (header)
    var headerHTML = `
        <div class="container mt-3">
            <h1 class="text-center">Bonjour, ${user ? user.name : 'Admin'}</h1>
        </div>
    `;

    // Insérer le header dans le conteneur avec l'ID "headerContainer"
    document.getElementById("headerContainer").innerHTML = headerHTML;

    // Créer le contenu principal de la page admin
    var adminContentHTML = `
        <div class="container mt-3">
            <div class="row p-5">
                <!-- Section "Ajouter Etudiant" -->
                <div class="col-md-3">
                    <a href="gestion_etudiants.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-4x text-danger"></i>
                                <h4>Gestion des Etudiants</h4>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section "Liste des étudiants" -->
                <div class="col-md-3">
                    <a href="liste_etudiants.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-edit fa-4x text-primary"></i>
                                <h4>Gestions des examens</h4>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section "Statistique" -->
                <div class="col-md-3">
                    <a href="statistique.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-5x text-warning"></i>
                                <h4>Statistique</h4>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section "Géo localisation" -->
                <div class="col-md-3">
                    <a href="localiser_les_etudiants.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt fa-5x text-success"></i>
                                <h4>Géo localisation</h4>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section "Gestion Images" -->
                <div class="col-md-3">
                    <a href="add_image.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-images fa-3x text-info"></i>
                                <h4>Gestion Images</h4>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Section "Gestion de fichier" -->
                <div class="col-md-3">
                    <a href="traitement.php">
                        <div class="card crd-ho" style="height: 180px;">
                            <div class="card-body text-center">
                                <i class="fas fa-folder-open fa-3x text-danger"></i>
                                <h4>Gestion de Fichier txt</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    `;

    // Insérer le contenu principal dans le conteneur "adminPageContent"
    document.getElementById("adminPageContent").innerHTML = adminContentHTML;
};
