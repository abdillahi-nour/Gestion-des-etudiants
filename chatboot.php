<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot de Messagerie</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #chatbox {
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .bot-message, .user-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .bot-message {
            background-color: #f1f1f1;
            text-align: left;
        }
        .user-message {
            background-color: #007bff;
            color: white;
            text-align: right;
        }
    </style>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
<div class="container-fluid" style="height: 10px; background-color: #303f9b;"></div>
<div class="container-fluid p-3 bg-light">
    <div class="row">
        <div class="col-md-3 text-success">
            <h3><i class="fas fa-tachometer-alt"></i> E-Project</h3>
        </div>
        <div class="col-md-6">
            <form class="form-inline my-2 my-lg-0" action="" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
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
<!-- End Header -->

    <div class="container mt-5">
        <h2 class="text-center">Chatbot de Messagerie POO</h2>
        <div id="chatbox" class="mb-3"></div>
        <div class="input-group">
            <input type="text" id="userInput" class="form-control" placeholder="Tapez votre message...">
            <div class="input-group-append">
                <button id="sendBtn" class="btn btn-primary">Envoyer</button>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer mt-auto">
    <p>&copy; 2024 Abdillahi Nour hassan. Tous droits réservés.</p>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script >document.addEventListener("DOMContentLoaded", () => {
        const chatbox = document.getElementById('chatbox');
        const userInput = document.getElementById('userInput');
        const sendBtn = document.getElementById('sendBtn');
    
        sendBtn.addEventListener('click', () => {
            const userMessage = userInput.value;
            if (userMessage.trim() !== '') {
                appendMessage(userMessage, 'user');
                getBotResponse(userMessage);
                userInput.value = '';
            }
        });
    
        userInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                sendBtn.click();
            }
        });
    
        function appendMessage(message, sender) {
            const messageElement = document.createElement('div');
            messageElement.classList.add(`${sender}-message`, 'message');
            messageElement.textContent = message;
            chatbox.appendChild(messageElement);
            chatbox.scrollTop = chatbox.scrollHeight;
        }
    
        function getBotResponse(userMessage) {
    let botMessage;
    if (/qu'est-ce que PHP/i.test(userMessage)) {
        botMessage = "PHP est un langage de script côté serveur utilisé principalement pour le développement web. Il peut générer des pages web dynamiques et interagir avec des bases de données.";
    } else if (/différence entre echo et print/i.test(userMessage)) {
        botMessage = "La différence principale entre echo et print en PHP est que echo peut afficher plusieurs chaînes de caractères à la fois sans parenthèses, tandis que print renvoie toujours 1 et nécessite des parenthèses.";
    } else if (/comment déclarer une variable en PHP/i.test(userMessage)) {
        botMessage = "En PHP, vous pouvez déclarer une variable en utilisant le symbole $ suivi du nom de la variable et en affectant une valeur, par exemple $nom = 'John';";
    } else if (/différence entre == et === en PHP/i.test(userMessage)) {
        botMessage = "== vérifie l'égalité des valeurs, tandis que === vérifie l'égalité des valeurs et des types de données en PHP.";
    } else if (/connexion à une base de données MySQL en PHP/i.test(userMessage)) {
        botMessage = "Pour se connecter à une base de données MySQL en PHP, vous pouvez utiliser la fonction mysqli_connect() ou PDO. Assurez-vous d'avoir les informations de connexion correctes.";
    } else if (/qu'est-ce qu'une session PHP/i.test(userMessage)) {
        botMessage = "Une session PHP est un moyen de stocker des informations sur un serveur pour une utilisation ultérieure pendant la navigation d'un utilisateur sur un site web. Vous pouvez démarrer une session en utilisant session_start();";
    } else if (/différence entre include et require en PHP/i.test(userMessage)) {
        botMessage = "include et require sont utilisés pour inclure des fichiers dans PHP. La principale différence est que si le fichier inclus n'est pas trouvé, include générera un avertissement et continuera l'exécution du script, tandis que require générera une erreur fatale et arrêtera l'exécution du script.";
    } else if (/gestion des erreurs et des exceptions en PHP/i.test(userMessage)) {
        botMessage = "En PHP, vous pouvez gérer les erreurs avec les instructions try, catch, finally pour les exceptions. Vous pouvez également utiliser la fonction set_error_handler() pour personnaliser la gestion des erreurs.";
    } else if (/fonction anonyme (closure) en PHP/i.test(userMessage)) {
        botMessage = "Une fonction anonyme, également appelée closure, est une fonction qui n'est pas associée à un nom spécifique. Vous pouvez la définir en utilisant la syntaxe function() {} et l'affecter à une variable pour une utilisation ultérieure.";
    } else if (/sécuriser un formulaire en PHP/i.test(userMessage)) {
        botMessage = "Pour sécuriser un formulaire en PHP, vous devez valider toutes les données entrantes, échapper les données avant de les afficher pour prévenir les attaques XSS, utiliser des jetons CSRF pour empêcher les attaques CSRF, et configurer correctement les permissions et les restrictions d'accès.";
    } else {
        botMessage = "Je suis un chatbot spécialisé dans PHP. Vous pouvez me poser des questions sur des sujets comme les variables, les conditions, les boucles, les fonctions, les sessions, la sécurité et bien plus encore.";
    }
    setTimeout(() => {
        appendMessage(botMessage, 'bot');
    }, 1000);
}

    });
    </script>

</body>
</html>
