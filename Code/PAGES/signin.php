<title>S'inscrire - UNI</title>
<link rel="stylesheet" href="CSS/signin.css">
<div class="container">
    <form class="signin-form" action="" method="post">
        <h1 class="signin-title">S'inscrire</h1>
        <div class="signin-inputs">
            <div class="email-div">
                <b>Email</b><br>
                <i class="fa-regular fa-user"></i>
                <input type="email" name="email" id="email" placeholder="Entrez votre Email">
            </div>
            <b id="bad-id">Email déjà utilisé</b>
            <input type="submit" name="submit" value="S'inscrire" id="submit">
        </div>
        <label>Déjà inscrit ?</label><br>
        <a href="?page=login" class="login-link"><b>SE CONNECTER</b></a><br>
        <div class="contact">
            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"><img class="contact-icon" src="IMGS/instagram.png" alt="" srcset=""></a>
            <a href="mailto:exemple@mail.com"><img class="contact-icon" src="IMGS/mail.png" alt="" srcset=""></a>
        </div>
    </form>
    
</div>

<?php

if (checkConnected()) {
    echo '<script>window.location = "index.php?page=profile"</script>';
}

if (isset($_POST["submit"])) {
    if (!empty($_POST["email"])) {
        $q = $db->prepare("SELECT * FROM users WHERE email = :email");
        $q->execute(['email' => $_POST["email"]]);
        $result = $q->fetch();

        
        if ($result == false) {
            
            echo '<script>window.location = "index.php?page=signin-form&email=' . $_POST["email"] .'"</script>';

        }else{
            echo '<script>badid("Adresse Email déjà utilisée.")</script>';
        }


    }else{
        echo '<script>badid("Veuillez remplir toutes les cases.")</script>';
    }
}


?>