<?php

require_once '_connec.php';
$pdo = new \PDO(DSN, USER, PASS);
// A exécuter afin d'afficher vos lignes déjà insérées dans la table friends
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

// $query2 = "DELETE FROM friend WHERE firstname LIKE 'P%'LIMIT 4 ";
// $statement2 = $pdo->exec($query2);

$flag = false;

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['firstname']) || trim($_POST['firstname']) === '' || strlen($_POST['firstname'])>45) 
        $errors[] = "Il faut rentrer un prénom de moins de 45 caractères";
    else
        $firstname = htmlentities($_POST['firstname']);
    
    if(!isset($_POST['lastname']) || trim($_POST['lastname']) === '' || strlen($_POST['lastname'])>45) 
        $errors[] = "Il faut rentrer un nom de moins de 45 caractères";
    else
        $lastname = htmlentities($_POST['lastname']);


    if (empty($errors)) {
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $statement->execute();
        $msg = '<div class=\'info\'><p>L\'ami a bien été ajouté</p></div>';
        header('Location: index.php');


    } else {
        $msg = '<div class=\'error\'><p>Visiblement des choses ne se sont pas passées comme prévu :</p>';
        $msg .= '<ul>';
        foreach ($errors as $error) {
            $msg .= '<li>' . $error . '</li>';
        }
        $msg .= '</ul></div>';
    }
    $flag = true;
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>WCS PDO 01</title>
</head>
<body>
  <header>
    <h1 id="title">Formulaire d'ajout d'ami</h1>
    <p id="description">Veuillez remplir le formulaire ci dessous pour rajouter un ami !</p>
  </header>

  <main>

    <?php 
            if ($flag) {
                echo $msg;
            }
        
        ?>



    <form action="" method="post" id="survey-form">
        <fieldset class="personnal-info">
            <div class="field">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Entrer votre prénom" required/>
            </div>

            <div class="field">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Entrer votre nom" required/>
            </div>
            <button type="submit" value="submit" id="submit">
                Envoyer
            </button>


        </fieldset>
    
        
    </form>

    <section class="friends">
        <h2>Voici les amis déjà présents :</h2>
        <ul>
            <?php foreach($friends as $friend): ?>
                <li><?= $friend['firstname'] . ' ' . $friend['lastname'] ?></li>
            <?php endforeach; ?>
        </ul>

    </section>




  </main>

  

  


    
</body>
</html>