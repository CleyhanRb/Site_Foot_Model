<?php

if (!checkConnected()) {
    echo '<script>window.location = "index.php?page=login"</script>';
}

?>
<title>Mon Profil - UNI</title>
<link rel="stylesheet" href="CSS/profile.css">
<div class="container">
    <h1>Votre profil:</h1>
    <div class="part-container">
        <div class="infos">
            <h2>Vos Informations:</h2>
            <div class="field-container">
                <form action="" method="post">
                    <div class="field">
                        <label for="">Nom</label>
                        <div>
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="lname" id="lname" value="<?= $_SESSION['user_lname']; ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="">Prénom</label>
                        <div>
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="fname" id="fname" value="<?= $_SESSION['user_fname']; ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="">Identifiant</label>
                        <div>
                            <i class="fa-regular fa-id-card"></i>
                            <input type="text" name="id" id="id" value="<?= $_SESSION['user_username']; ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="">Email</label>
                        <div>
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" id="email" value="<?= $_SESSION['user_email']; ?>">
                        </div>
                    </div>
                    <b id="bad-id">Email ou Mot de Passe incorrect</b>
                    <input type="submit" id="submit" name="submit" value="Sauvegarder les modifications">
                </form>
            </div>
        </div>
        <div class="part-bets">
            <h2>Vos paris:</h2>
            <div class="bets">
                <?php
                
                $getBets = $db->prepare('SELECT * FROM bets WHERE player_id = ?');
                $getBets->execute(array($_SESSION['user_id']));
                
                if ($getBets->rowCount() > 0) {
                    $bets = $getBets->fetchAll();
                    foreach ($bets as $key => $value) {
                        $getMatch = $db->prepare('SELECT * FROM matchs WHERE id = ?');
                        $getMatch->execute(array($value['match_id']));
                        $match = $getMatch->fetch();
                        ?>
                        <div class="match">
                            <h3><?= $match['teamA'] . ' - ' . $match['teamB']; ?></h3>
                            <div class="bet-line">
                                <b>Pari:</b>
                                <label>
                                    <?php
                                    
                                    switch ($value['choice']) {
                                        case 0:
                                            echo $match['teamA'];
                                            break;
                                        case 1:
                                            echo $match['teamB'];
                                            break;
                                            case 2:
                                                echo "Nul";
                                                break;
                                        default:
                                            echo "Error";
                                            break;
                                    }
                                    
                                    ?>
                                </label>
                            </div>
                            <div class="bet-line">
                                <b>Mise:</b><label><?= $value['bet']; ?> <i class="fa-solid fa-coins"></i></label>
                            </div>
                            <div class="bet-line">
                                <b>Etat:</b><label>
                                <?php
                                
                                switch ($value['status']) {
                                    case 0:
                                        echo "En Attente...";
                                        break;
                                    case 1:
                                        echo '<span class="state-lost">Perdu</span>';
                                        break;
                                    case 2:
                                        echo '<a href="?action=get&match=' . $value['match_id'] . '&id=' . $_SESSION['user_id'] .'" class="state-won">Gagnant</a>';
                                        break;
                                    case 3:
                                        echo '<span class="state-got">Récupéré</span>';
                                        break;        
                                    default:
                                        echo "Error";
                                        break;
                                }
                                
                                ?>    
                                </label>
                            </div>
                        </div>
                        <?php
                    }
                }else{
                    echo "Vous n'avez pas encore parié.";
                }
                
                ?>
                
            </div>
        </div>
    </div>
</div>

<?php

if (isset($_POST["submit"])) {
    if (isset($_POST["lname"]) && isset($_POST["fname"]) && isset($_POST["id"]) && isset($_POST["email"])) {
        $email = htmlspecialchars($_POST["email"]);
        $user_id = htmlspecialchars($_POST["id"]);
        $lname = htmlspecialchars($_POST["lname"]);
        $fname = htmlspecialchars($_POST["fname"]);
        if (!empty($lname) && !empty($fname) && !empty($user_id) && !empty($email)) {
            
            $checkdata = $db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $checkdata->execute(array($userid, $email, $_SESSION['user_id']));

            if ($checkdata->rowCount() == 0) {
                
                $updateUser = $db->prepare("UPDATE users SET lastname = ?, firstname = ?, username = ?, email = ? WHERE id = ?");
                $updateUser->execute(array($lname, $fname, $user_id, $email, $_SESSION['user_id']));


                $q = $db->prepare("SELECT * FROM users WHERE id = :id");
                $q->execute(['id' => $_SESSION["user_id"]]);
                $result = $q->fetch();

                $_SESSION['user_username'] = $result['username'];
                $_SESSION['user_email'] = $result['email'];
                $_SESSION['user_lname'] = $result['lastname'];
                $_SESSION['user_fname'] = $result['firstname'];
                $_SESSION['user_rank'] = $result['rank'];
                $_SESSION['user_secured'] = $result['secured'];
                $_SESSION['user_credits'] = $result['credits'];
                echo "<script>window.location = 'index.php?page=profile'</script>";

            }else{
                echo '<script>badid("Cet identifiant ou utilisateur est déjà utilisé.")</script>';
            }


        } else {
            echo '<script>badid("Veuillez remplir toutes les cases.")</script>';
        }
    }
}

?>


<script src="JS/profile.js"></script>
