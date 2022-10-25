<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="shortcut icon" href="./img/favicon.webp" type="image/x-icon">
    <title>Les Argonautes</title>
</head>


<body>
    <header>
        <h1>
            <img src="https://www.wildcodeschool.com/assets/logo_main-e4f3f744c8e717f1b7df3858dce55a86c63d4766d5d9a7f454250145f097c2fe.png" alt="Wild Code School logo" />
            Les Argonautes
        </h1>
    </header>

    <!-- Main section -->
    <main>

        <!-- New member form -->
        <h2>Ajouter un(e) Argonaute</h2>
        <form class="new-member-form" method="POST" action="index.php">
            <label for="name">Nom de l&apos;Argonaute</label>
            <input id="name" name="name" type="text" placeholder="Charalampos" />
            <button type="submit" name="addArg" value="0">Envoyer</button>
        </form>
        <?php
        /* db connexion */
        try {
            $db = new PDO('mysql:host=localhost;dbname=wildCodeSchoolTest;charset=utf8', 'user1', 'user1');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        /*Fetching argonautes from the db*/
        $sql = "SELECT name from argonautes";
        $query = $db->prepare($sql);
        $query->execute();
        $argoTeam = $query->fetchAll();
        if (isset($_POST['addArg'])) {
            /*Checking that name is set and not empty  */
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                /*if that's the case we as the value of the field into a variables(strip tags will remove html and php tag)*/
                $name = strip_tags($_POST['name']);
                $total = count($argoTeam);
                $list = array();
                for ($i = 0; $i <= $total - 1; $i++) {
                    array_push($list, $argoTeam[$i]['name']);
                }
                $look = array_search($name, $list);
                if ($look == false) {
                    /* sending the data to the db while protecting ourself from sql injection with a prepared query*/
                    $sql = "INSERT INTO argonautes (name) Value (:name)";
                    $query = $db->prepare($sql);
                    $query->bindValue(':name', $name, PDO::PARAM_STR_CHAR);
                    $query->execute();
                    /*Success message */
                    echo '<p class ="checked message">Argonaute ajouté(e)</p>';
                } else {

                    echo '<p class ="double message">L\'argonaute est déjà à bord</p>';
                }
            } elseif (isset($_POST['name']) && empty($_POST['name'])) {
                /* Error message*/
                echo '<p class ="error message">Veuillez entrer un nom</p>';
            }
        } ?>

        <!-- Member list -->
        <h2>Membres de l'équipage</h2>
        <section class="member-list">
            <?php foreach ($argoTeam as $member) { ?>
                <div class="member-item"><?= $member['name'] ?></div>
            <?php } ?>
        </section>
    </main>

    <footer>
        <p>Réalisé par Jason en Anthestérion de l'an 515 avant JC</p>
    </footer>
</body>

</html>