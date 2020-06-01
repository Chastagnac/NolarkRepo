<!DOCTYPE html>
<?php
include('../includes/debut_page.php');
?>

<html lang="fr-FR">
    <head>
        <title>Casques Nolark : Sécurité et confort, nos priorités !</title>
        <meta charset="UTF-8">
        <meta name="author" content="José GIL">
        <meta name="description" content="Découvrez des casques moto dépassant même les exigences des tests de sécurité. Tous les casques Nolark au meilleur prix et avec en prime la livraison gratuite !">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/styles.css" rel="stylesheet" type="text/css">
        <link href="../css/contact.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../favicon.ico">
    </head>
    <body>
        <?php
        include('../includes/header.html.inc.php');
        ?>
        <h1 id="teste">Connexion :</h1>
        <?php
//Le cas ou l'utilisateur n'est pas encore connecté, on lui affiche donc le formulaire de connection
        if (!filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING) && !isset($_SESSION['pseudo'])) {
            echo '<form method="post" action="connexion.php">
	<fieldset>
	<legend>Connexion</legend>
	<p>
	<label for="pseudo">Pseudo :</label><input name="pseudo" type="text" id="pseudo" /><br />
	<label for="password">Mot de Passe :</label><input type="password" name="password" id="password" />
	</p>
	</fieldset>
	<p><input type="submit" value="Connexion" /></p></form>
	<a href="./inscription.php" class="hrefconnexion">Pas encore inscrit ?</a>
	 
	</div>';
        } else {
            $message = '';
            //Au cas ou l'utilisateur était déjà connecté
            if (isset($_SESSION['pseudo'])) {
                $message = '<p>Page inaccessible car vous êtes déjà connecté </p>'
                        . '<p>Cliquez <a href="../index.php">ici</a> pour revenir à l\'accueil</p>';
            }
            //On vérifie si aucun champs n'a été oublié
            else if (!filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING) || !filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)) {
                $message = '<p>une erreur s\'est produite pendant votre identification.
	Vous devez remplir tous les champs</p>
	<p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
            } else { //On check le mot de passe
                $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
                $db = new PDO('mysql:host=127.0.0.1;dbname=nolark', 'nolarkuser', 'nolarkpwd');
                $query = $db->prepare('SELECT login, password, niveau from utilisateur where login= :pseudo');
                $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
                $query->execute();
                $data = $query->fetch();
                if ($data['password'] == filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)) { // Acces OK !
                    $_SESSION['pseudo'] = $pseudo;
                    $_SESSION['niveau'] = $data['niveau'];
                    $message = '<p>Bienvenue ' . $data['login'] . ', 
			vous êtes maintenant connecté!</p>
			<p>Cliquez <a href="../index.php">ici</a> 
			pour revenir à la page d accueil</p>
                        <p>Cliquez <a href="./mon-compte.php">ici</a> 
			pour aller sur votre page personelle</p>';
                } else { // Acces pas OK !
                    $message = '<p>Une erreur s\'est produite 
	    pendant votre identification.<br /> Le mot de passe ou le pseudo 
            entré n\'est pas correcte.</p><p>Cliquez <a href="./connexion.php">ici</a> 
	    pour revenir à la page précédente
	    <br /><br />Cliquez <a href="./index.php">ici</a> 
	    pour revenir à la page d accueil</p>';
                }
                $query->CloseCursor();
            }
            echo $message . '</div>';           
        }
         
        include('../includes/Réseaux.php');
        include('../includes/footer.inc.php');
        ?>
    </body>
</html>

