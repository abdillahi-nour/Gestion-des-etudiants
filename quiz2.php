<?php
session_start();
require_once 'db/Db_project.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
  header('Location: home.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Les données sont envoyées via POST et on récupère le score
    if (isset($_POST['score'])) {
        $score = $_POST['score'];
    } else {
        echo "Score non reçu";
        exit;
    }

    // Utilisez l'ID de l'étudiant connecté pour mettre à jour la note1
    $userId = $_SESSION['user']['id']; // Supposons que l'ID de l'étudiant est dans $_SESSION['user']['id']

    try {
        // $conn est une instance de PDO 
        if (!$conn) {
            echo "Erreur de connexion à la base de données";
            exit;
        }

        // Récupérez la note2 de l'étudiant
        $sqlFetchNote2 = "SELECT note1 FROM etudiants WHERE id = :id";
        $stmtFetchNote2 = $conn->prepare($sqlFetchNote2);
        $stmtFetchNote2->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtFetchNote2->execute();
        $row = $stmtFetchNote2->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo "Erreur : Étudiant non trouvé";
            exit;
        }

        $note1 = $row['note1'];

        // Calcul de la moyenne
        $moyenne = ($score + $note1) / 2;

        // Mettre à jour la note1 et la moyenne de l'utilisateur
        $sql = "UPDATE etudiants SET note2 = :score, moyenne = :moyenne WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Vérifier si la préparation de la requête a réussi
        if (!$stmt) {
            echo "Erreur lors de la préparation de la requête";
            exit;
        }

        $stmt->bindParam(':score', $score, PDO::PARAM_INT);
        $stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_INT);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Score et moyenne mis à jour avec succès";
        } else {
            echo "Erreur lors de la mise à jour du score et de la moyenne";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz</title>
  <link rel="stylesheet" href="all_component/styles_quizz.css">
  <link rel="stylesheet" href="styles.css">

</head>
<body>
<!-- Header -->
<?php include 'all_component/header3.php'; ?>
<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-sticky">
            <h5 class="sidebar-heading p-3">Dashboard</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="home.php"><i class="fas fa-user"></i> Mon profil</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cv_anh.pdf"><i class="fas fa-address-card"></i> À propos de moi</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="matrice.html" onclick="loadContent('matrice.html')"><i class="fas fa-calculator"></i> Manipulation de matrices</a>
                </li>
              
                <li class="nav-item">
                   <a class="nav-link" href="quiz1.php"><i class="fas fa-question-circle fa-2x text-infos"></i> Quiz Javascript</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="quiz2.php"><i class="fas fa-question-circle fa-2x text-infos"></i> Quiz PHP</a>
                </li>
                
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
    <h1 id="title">Quiz 2 php</h1>
  <div id="quiz">
    <div id="question-container">
      <!-- Questions will be injected here -->
    </div>
    <button id="prev" style="display: none;">Précédent</button>
    <button id="next">Suivant</button>
    <button id="submit" style="display: none;">Soumettre</button>
  </div>
  
  <div id="results" style="display: none;">
    <h2 id="result-title">Résultats</h2>
    <p id="score"></p>
    <p id="feedback"></p>
  </div>

        </div>
    </div>
</div>


    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto">
    <p>&copy; 2024 Abdillahi Nour hassan. Tous droits réservés.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function loadContent(page) {
        fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById('mainContent').innerHTML = data;
            })
            .catch(error => console.error('Erreur lors du chargement du contenu :', error));
    }
</script>
<script>
 const questions = [
    {
        question: "Quelle est l'extension correcte pour un fichier PHP ?",
        choices: [".html", ".php", ".css", ".js"],
        answer: 1
    },
    {
        question: "Comment affiche-t-on du texte en PHP ?",
        choices: ["echo 'texte';", "print('texte');", "display 'texte';", "text('texte');"],
        answer: 0
    },
    {
        question: "Quelle variable superglobale est utilisée pour accéder aux variables de session en PHP ?",
        choices: ["$_POST", "$_GET", "$_SESSION", "$_COOKIE"],
        answer: 2
    },
    {
        question: "Quelle fonction est utilisée pour inclure un fichier en PHP ?",
        choices: ["include()", "require()", "include_once()", "Toutes les réponses sont correctes"],
        answer: 3
    },
    {
        question: "Comment déclare-t-on une fonction en PHP ?",
        choices: ["function maFonction()", "def maFonction()", "function: maFonction()", "fonction maFonction()"],
        answer: 0
    },
    {
        question: "Quelle commande PHP est utilisée pour obtenir la longueur d'une chaîne de caractères ?",
        choices: ["strlen()", "length()", "strlength()", "count()"],
        answer: 0
    },

    {
        question: "Comment démarrez-vous une session en PHP ?",
        choices: ["session_start();", "start_session();", "begin_session();", "session_begin();"],
        answer: 0
    },
    {
        question: "Quel symbole est utilisé pour commenter une seule ligne en PHP ?",
        choices: ["//", "#", "/* */", "<!-- -->"],
        answer: 0
    },
    {
        question: "Comment accédez-vous à un élément d'un tableau associatif en PHP ?",
        choices: ["$tableau['clé']", "$tableau->'clé'", "$tableau['valeur']", "$tableau->clé"],
        answer: 0
    },
    // Ajout de nouvelles questions
    {
        question: "Quelle fonction PHP est utilisée pour convertir une chaîne en entier ?",
        choices: ["intval()", "strval()", "convert()", "toInt()"],
        answer: 0
    },
    {
        question: "Quelle fonction PHP est utilisée pour inclure un fichier seulement une fois ?",
        choices: ["include_once()", "include()", "require_once()", "require()"],
        answer: 0
    },
    {
        question: "Quelle est la méthode correcte pour ouvrir un fichier en mode lecture en PHP ?",
        choices: ["fopen('fichier.txt', 'r')", "open('fichier.txt', 'r')", "read('fichier.txt')", "file_open('fichier.txt', 'r')"],
        answer: 0
    },
    {
        question: "Quelle fonction PHP est utilisée pour vérifier si une variable est un nombre ?",
        choices: ["is_numeric()", "is_number()", "is_int()", "is_integer()"],
        answer: 0
    },
    {
        question: "Comment convertir un tableau en une chaîne en PHP ?",
        choices: ["implode()", "explode()", "join()", "concatenate()"],
        answer: 0
    },
    {
        question: "Quelle est la syntaxe correcte pour créer une classe en PHP ?",
        choices: ["class MaClasse {}", "define class MaClasse {}", "create class MaClasse {}", "new class MaClasse {}"],
        answer: 0
    },
    {
        question: "Quelle fonction PHP est utilisée pour calculer le hash d'une chaîne ?",
        choices: ["hash()", "md5()", "sha1()", "crypt()"],
        answer: 1
    },
    {
        question: "Comment supprime-t-on un élément d'un tableau en PHP ?",
        choices: ["unset()", "remove()", "delete()", "discard()"],
        answer: 0
    },
    {
        question: "Comment convertir un tableau en une chaîne en PHP ?",
        choices: ["implode()", "explode()", "join()", "concatenate()"],
        answer: 0
    },
    {
        question: "Quelle fonction PHP est utilisée pour trier un tableau en ordre croissant ?",
        choices: ["sort()", "asort()", "ksort()", "rsort()"],
        answer: 0
    },
    {
        question: "Quelle fonction PHP est utilisée pour vérifier si un fichier existe ?",
        choices: ["file_exists()", "file_exist()", "is_file()", "check_file()"],
        answer: 0
    }
];

// Exemple de parcours des questions
questions.forEach(question => {
    console.log("Question: " + question.question);
    console.log("Choix:");
    question.choices.forEach((choice, index) => {
        console.log(`  ${index}: ${choice}`);
    });
    console.log("Réponse correcte: " + question.choices[question.answer] + "\n");
});


  // Variables
  let currentPage = 0;
  const questionsPerPage = [3, 3, 4];
  let answers = new Array(questions.length).fill(null);
  let score = 0;

  // Éléments HTML
  const questionContainer = document.getElementById("question-container");
  const prevButton = document.getElementById("prev");
  const nextButton = document.getElementById("next");
  const submitButton = document.getElementById("submit");
  const resultsElement = document.getElementById("results");
  const scoreElement = document.getElementById("score");
  const feedbackElement = document.getElementById("feedback");

  // Fonctions
  function displayQuestions() {
    const start = questionsPerPage.slice(0, currentPage).reduce((a, b) => a + b, 0);
    const end = start + questionsPerPage[currentPage];
    const currentQuestions = questions.slice(start, end);
    
    questionContainer.innerHTML = "";

    currentQuestions.forEach((questionData, index) => {
      const questionDiv = document.createElement("div");
      questionDiv.classList.add("question");

      const questionTitle = document.createElement("h2");
      questionTitle.textContent = `Q ${start + index + 1}: ${questionData.question}`;
      questionDiv.appendChild(questionTitle);

      const choicesList = document.createElement("ul");
      choicesList.classList.add("choices");
      questionData.choices.forEach((choice, choiceIndex) => {
        const li = document.createElement("li");
        const radio = document.createElement("input");
        radio.type = "radio";
        radio.name = `question${start + index}`;
        radio.value = choiceIndex;
        radio.checked = answers[start + index] === choiceIndex;
        li.appendChild(radio);
        li.appendChild(document.createTextNode(choice));
        choicesList.appendChild(li);
      });

      questionDiv.appendChild(choicesList);
      questionContainer.appendChild(questionDiv);
    });
    
    prevButton.style.display = currentPage > 0 ? "inline-block" : "none";
    nextButton.style.display = currentPage < questionsPerPage.length - 1 ? "inline-block" : "none";
    submitButton.style.display = currentPage === questionsPerPage.length - 1 ? "inline-block" : "none";
  }

  function collectAnswers() {
    const start = questionsPerPage.slice(0, currentPage).reduce((a, b) => a + b, 0);
    const end = start + questionsPerPage[currentPage];
    for (let i = start; i < end; i++) {
      const selectedOption = document.querySelector(`input[name="question${i}"]:checked`);
      if (selectedOption) {
        answers[i] = parseInt(selectedOption.value, 10);
      }
    }
  }

  function calculateScore() {
    score = answers.reduce((total, answer, index) => {
      return total + (answer === questions[index].answer ? 2 : 0);
    }, 0);
  }
  function displayResults() {
  calculateScore();
  questionContainer.style.display = "none";
  prevButton.style.display = "none";
  nextButton.style.display = "none";
  submitButton.style.display = "none";
  resultsElement.style.display = "block";
  
  scoreElement.textContent = `Votre score : ${score} / ${questions.length}`;
  
  if (score === questions.length) {
    feedbackElement.textContent = "Félicitations ! Vous avez répondu correctement à toutes les questions.";
  } else {
    feedbackElement.textContent = "Bien joué ! Vous pouvez encore vous améliorer.";
  }

  // Envoi du score au serveur
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log("Score mis à jour avec succès");
    }
  };
  const userId = <?php echo isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0; ?>;
  xhr.send("userId=" + userId + "&score=" + score);
}


  // Démarrage du quiz
  displayQuestions();

  nextButton.addEventListener("click", () => {
    collectAnswers();
    currentPage++;
    displayQuestions();
  });

  prevButton.addEventListener("click", () => {
    collectAnswers();
    currentPage--;
    displayQuestions();
  });

  submitButton.addEventListener("click", () => {
    collectAnswers();
    displayResults();
  });
  </script>


</body>
</html>
