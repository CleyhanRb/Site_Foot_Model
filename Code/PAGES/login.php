<title>Se Connecter - UNI</title>
<link rel="stylesheet" href="CSS/login.css">
<div class="container">
    <form class="login-form" method="post">
        <h1 class="login-title">Se Connecter</h1>
        <div class="login-inputs">
            <div class="login-div">
                <b>Email</b><br>
                <i class="fa-regular fa-user"></i>
                <input type="email" name="email" id="email" placeholder="Entrez votre Email">
            </div>
            <div class="password-part">
                <div class="password-div">
                    <b>Mot de Passe</b><br>
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="passwd" id="passwd" placeholder="Entrez votre mot de passe">
                </div>
                <!-- <a href="" class="password-reset">Mot de passe oublié ?</a> -->
            </div>
            <b id="bad-id">Email ou Mot de Passe incorrect</b>
            <input type="submit" name="submit" value="Se connecter" id="submit">
        </div>
        <label>Pas encore inscrit ?</label><br>
        <a href="?page=signin" class="signup-link"><b>S'INSCRIRE</b></a><br>
        <div class="contact">
            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"><img class="contact-icon" src="IMGS/instagram.png" alt="" srcset=""></a>
            <a href="mailto:exemple@mail.com"><img class="contact-icon" src="IMGS/mail.png" alt="" srcset=""></a>
        </div>
    </form>
    
</div>

<?php

if (checkConnected()) {
    header("Location: ?page=profile");
}

if (isset($_POST["submit"])) {
    if (!empty($_POST["email"]) && !empty($_POST["passwd"])) {
        $q = $db->prepare("SELECT * FROM users WHERE email = :email");
        $q->execute(['email' => $_POST["email"]]);
        $result = $q->fetch();

        
        if ($result == true) {
            // Le Compte Existe
            $hashpassword = $result['password'];
            if (password_verify($_POST["passwd"], $hashpassword)) {
                //Tout est bon

                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_username'] = $result['username'];
                $_SESSION['user_email'] = $result['email'];
                $_SESSION['user_lname'] = $result['lastname'];
                $_SESSION['user_fname'] = $result['firstname'];
                $_SESSION['user_rank'] = $result['rank'];
                $_SESSION['user_secured'] = $result['secured'];
                $_SESSION['user_credits'] = $result['credits'];

                // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                //     $ip = $_SERVER['HTTP_CLIENT_IP'];
                // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                // } else {
                //     $ip = $_SERVER['REMOTE_ADDR'];
                // }
                
                // $updateIP = $db->prepare("UPDATE users SET ip = ? WHERE id = ?");
                // $updateIP->execute(array($ip, $result['id']));
                

                echo '<script>window.location = "index.php"</script>';
                
            }else{
                echo '<script>badid("Mot de passe incorrect.")</script>';
            }

        }else{
            echo '<script>badid("Email introuvable.")</script>';
        }


    }else{
        echo '<script>badid("Veuillez remplir toutes les cases.")</script>';
    }
}


?>