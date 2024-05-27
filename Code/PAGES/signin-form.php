<title>S'inscrire - UNI</title>
<link rel="stylesheet" href="CSS/signin-form.css">
<div class="container">
    <h1 class="title">Finir l'inscription</h1>
    <h3>Afin de finir l'inscription, merci de bien vouloir remplir les informations suivantes: </h3>

    <form action="" method="post">
        <div class="signin-inputs">
            

            <div class="name-div field-container">
                <div class="lname-div field">
                    <b>Nom</b>
                    <div>
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="lastname" id="lastname" placeholder="Entrez votre nom de famille">
                    </div>
                </div>
                
                <div class="fname-div field">
                    <b>Prénom</b>
                    <div>
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="firstname" id="firstname" placeholder="Entrez votre prénom">
                    </div>
                </div>
            </div>

            <div class="field-container">
                <div class="id-div field">
                    <b>Identifiant</b>
                    <div>
                        <i class="fa-regular fa-id-card"></i>
                        <input type="text" name="id" id="id" placeholder="Entrez un identifiant. ex: uni.bde">
                    </div>
                </div>

                <div class="email-div field">
                    <b>Email</b>
                    <div>
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_GET["email"]); ?>" placeholder="Entrez votre Email">
                    </div>
                </div>
            </div>


            <div class="grade-div field">
                <b>Classe</b>
                <div>
                    <i class="fa-solid fa-school"></i>
                    <input type="text" name="grade" id="grade" placeholder="Entrez votre Classe. ex: 109">
                </div>
            </div>
            <div class="password-part field-container">
                <div class="password-div field">
                    <b>Mot de Passe</b>
                    <div>
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="passwd" id="passwd" placeholder="Entrez votre mot de passe">
                    </div>
                </div>
                <div class="password-div field">
                    <b>Confirmez votre Mot de Passe</b>
                    <div>
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="passwdc" id="passwdc" placeholder="Confirmez votre mot de passe">
                    </div>
                </div>
            </div>
            <b id="bad-id">Erreur</b><br>
            <input type="submit" name="submit" value="S'inscrire" id="submit">
        </div>
    </form>
</div>

<?php 

if (checkConnected()) {
    echo '<script>window.location = "index.php?page=profile"</script>';
}

if(isset($_POST['submit'])) {

    if (!empty($_POST['lastname']) && !empty($_POST['firstname']) &&
    !empty($_POST['id']) && !empty($_POST['email']) &&
    !empty($_POST['grade']) && 
    !empty($_POST['passwd']) && !empty($_POST['passwdc'])) {
        
        $qmail = $db->prepare("SELECT * FROM users WHERE email = :email");
        $qmail->execute(['email' => $_POST['email']]);
        $resultmail = $qmail->fetch();

        if ($resultmail == false) {
            
            $quser = $db->prepare("SELECT * FROM users WHERE username = :username");
            $quser->execute(['username' => $_POST['id']]);
            $resultuser = $quser->fetch();

            if ($resultuser == false) {
                $options = [
                    'cost' => 12,
                ];
                $hashpass = password_hash($_POST['passwdc'], PASSWORD_BCRYPT, $options);
                $token = bin2hex(random_bytes(35));
    
                if (password_verify($_POST['passwd'], $hashpass)) {
                    $q = $db->prepare("INSERT INTO users(username,email,lastname,firstname,grade,password, token) VALUES(:username,:email,:lastname,:firstname,:grade,:password,:token)");
                    $q->execute([
                    'username' => htmlspecialchars($_POST['id']),
                    'email' => htmlspecialchars($_POST['email']),
                    'lastname' => htmlspecialchars($_POST['lastname']),
                    'firstname' => htmlspecialchars($_POST['firstname']),
                    'grade' => htmlspecialchars($_POST['grade']),
                    'password' => $hashpass,
                    'token' => $token
                    ]);
                    echo '<script>window.location = "index.php?page=login"</script>';
                } else {
                    echo '<script>badid("Les mots de passes ne sont pas identiques")</script>';
                }
            }else{
                echo '<script>badid("Un compte avec cet identifiant existe déjà.")</script>';
            }

        }else{
            echo '<script>badid("Un compte avec cette adresse email existe déjà.")</script>';
        }

    }else{
        echo '<script>badid("Veuillez remplir toutes les cases.")</script>';
    }
    
    
}



?>
