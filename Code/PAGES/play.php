<?php

if (isset($_GET["match"])) {
    if (checkConnected()) {
        $getMatch = $db->prepare("SELECT * FROM matchs WHERE id = ? AND status = 0");
        $getMatch->execute(array($_GET["match"]));
        $match = $getMatch->fetch();
        
        if (!$match) {
            echo '<script>window.location = "index.php"</script>';
        }

        $_SESSION['match_id'] = $match['id'];
        $cotes = cotesMatch($match["id"]);

    } else {
        echo '<script>window.location ="index.php?page=login"</script>';
    }
} else {
    echo '<script>window.location = "'. get_base_url() . '"</script>';
}
?>
<title>Jouer - UNI</title>
<link rel="stylesheet" href="CSS/play.css">
<div class="container">
    <h1 class="title">Match:
        <?= $match["teamA"] . " - " . $match["teamB"]; ?>
    </h1>
    <h3>Faites votre choix et entrez votre mise:</h3>

    <form action="index.php?action=bet" method="post">
        <div class="options">
            <label for="rTeamA">
                <div class="bet-option">
                    <input type="radio" name="bChoice" id="rTeamA" value="0">
                    <h1><?= $match["teamA"]; ?></h1>
                    <h3>x<span class="coef"><?= $cotes[0]; ?></span></h3>
                </div>
            </label>
            <label for="rTeamB">
                <div class="bet-option">
                    <input type="radio" name="bChoice" id="rTeamB" value="2">
                    <h1>Nul</h1>
                    <h3>x<span class="coef"><?= $cotes[2]; ?></span></h3>
                    
                </div>
            </label>
            <label for="rEqual">
                <div class="bet-option">
                    <input type="radio" name="bChoice" id="rEqual" value="1">
                    <h1><?= $match["teamB"]; ?></h1>
                    <h3>x<span class="coef"><?= $cotes[1]; ?></span></h3>
                </div>
            </label>
        </div>
        <div class="field-container">
            <div class="field">
                <b>Mise: </b>
                <div>
                    <i class="fa-solid fa-coins"></i>
                    <input type="number" name="bet" id="bet" placeholder="Entrez la mise à parier" value="0" min="0" max="<?= $_SESSION['user_credits']; ?>">
                </div>
            </div>
            <h1><i class="fa-solid fa-arrow-right"></i></h1>
            <div class="field calc">
                <div class="earnings">
                    <b>Gains gagnant: </b>
                    <label id="earnings">0</label>
                </div>
                <i class="fa-solid fa-arrow-right"></i>
                <div class="gains">
                    <b>Plus Value: </b>
                    <label id="gains">0</label>
                </div>
            </div>
        </div>
        <input type="submit" id="submit" value="Parier !">
    </form>
</div>
<script src="JS/play.js"></script>
