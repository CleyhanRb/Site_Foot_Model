<?php 

$GLOBALS['VERSION'] = "Beta v1.0";

include "PHP/required.php";
session_start();

// if (session_status() === PHP_SESSION_NONE){session_start();}
// $_SESSION = array();
// session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/menu.css">
        <link rel="stylesheet" href="CSS/base.css">
        <link rel="icon" type="image/png" href="IMGS/logo.png" />
        
    </head>
    <body>
        
        <header class="header">
            <img class="menu-logo" src="IMGS/logo.png" alt="Logo">
            <h4 class="menu-title">[Groupe] - [Événement]</h4>
            <!-- <h4 class="menu-title">UNI BDE - Tournois de Football - <?= $GLOBALS['VERSION']; ?></h4> -->
            <!-- <input type="checkbox" name="" id="color"> -->
            <?php
            if (checkConnected()) {
                ?>
                <li class="li-credits"><?= $_SESSION['user_credits']; ?> <i class="fa-solid fa-coins"></i></li>
                <?php
            }
            ?>
            <input class="menu-btn" type="checkbox" id="menu-btn"/>
            <label class="menu-style" for="menu-btn"><span class="menu-style-line"></span></label>
            <nav class="nav">
                <ul class="menu-items">
                    <li><a href="index.php" class="menu-item">Accueil</a></li>
                    <li><a href="?page=rules" class="menu-item">Règles</a></li>
                    <li><a href="?page=edt" class="menu-item">Planning</a></li>
                    <?php 
                    
                    if(checkConnected()){
                        ?>
                        <script>const playerMoney = <?= $_SESSION['user_credits']; ?></script>
                        <li><a href="?page=profile" class="menu-item">Mon Profil</a></li>
                        <li><a href="?action=logout" class="menu-item">Déconnexion</a></li>
                        <?php

                        if ($_SESSION['user_rank'] <= 5) {
                            ?>
                            <style>
                            
                            :root{
                                --main-forecolor: #831113;
                                --menu-backcolor-secondary: #9b6464;
                            }
                            
                            </style>
                            <li><a href="?page=admin" style="color: red !important" class="menu-item">Administration</a></li>
                            <?php
                        }
                    }else{
                        ?>
                        
                        <li><a href="?page=login" class="menu-item">Se Connecter</a></li>
                        <li><a href="?page=signin" class="menu-item">S'inscrire</a></li>
                        
                        <?php
                    }

                    ?>
                </ul>
            </nav>
        </header>

    </body>
</html>
