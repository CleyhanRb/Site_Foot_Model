<?php

if (!checkConnected() || $_SESSION['user_rank'] > 5) {
    die('<script>window.location = "index.php"</script>');
}

if (isset($_POST['submit-create'])) {
    if (isset($_POST['teamA']) && !empty($_POST['teamA']) && 
    isset($_POST['teamB']) && !empty($_POST['teamB']) && 
    isset($_POST['date']) && !empty($_POST['date'])) {
        
        $createMatch = $db->prepare('INSERT INTO matchs(teamA,teamB,date) VALUE(?,?,?)');
        $createMatch->execute(array($_POST['teamA'],$_POST['teamB'],$_POST['date']));

    }
}
if (isset($_POST['submit-edit'])) {
    if (isset($_POST['match-select']) && $_POST['match-select'] != '' && 
    isset($_POST['state-select']) && $_POST['state-select'] != '') {
        $editMatch = $db->prepare('UPDATE matchs SET status = ? WHERE id = ?');
        $editMatch->execute(array($_POST['state-select'],$_POST['match-select']));
    }
}

if (isset($_POST['submit-end'])) {
    if (isset($_POST['match-select']) && $_POST['match-select'] != '' &&
    isset($_POST['teamA']) && $_POST['teamA'] != '' && 
    isset($_POST['teamB']) && $_POST['teamB'] != '') {

        if ($_POST['teamA'] < $_POST['teamB']) { $winner = 1; }
        else if ($_POST['teamA'] > $_POST['teamB']) { $winner = 0; }
        else { $winner = 2; }

        $cotes = cotesMatch($_POST['match-select']);

        $editMatch = $db->prepare('UPDATE matchs SET status = 2, coef_teamA = ?, coef_teamB = ?, coef_equal = ?, winner = ?, ptsA = ?, ptsB = ? WHERE id = ?');
        $editMatch->execute(array($cotes[0], $cotes[1], $cotes[2], $winner, $_POST['teamA'], $_POST['teamB'] ,$_POST['match-select']));
        
        
        $editBetsLoses = $db->prepare("UPDATE bets SET status = 1 WHERE match_id = ? AND choice != ?");        
        $editBetsLoses->execute(array(intval($_POST['match-select']), $winner));

        $editBetsWin = $db->prepare('UPDATE bets SET status = 2 WHERE match_id = ? AND choice = ?');
        $editBetsWin->execute(array(intval($_POST['match-select']), $winner));

    }
}
?>
<title>Administration - UNI</title>
<link rel="stylesheet" href="CSS/admin.css">
<div class="container">
    <h1>Page d'Administration</h1>
    <div class="parts">
        <div class="part">
            <h2>Matchs</h2>
            <div class="subparts">
                <form class="createMatch subpart" method="post">
                    <h3>Créer un nouveau match</h3>
                    <div class="field">
                        <b>Équipe A</b>
                        <div>
                            <i class="fa-solid fa-people-group"></i>
                            <input type="text" name="teamA" id="teamA" placeholder="Entrez la classe de l'équipe A">
                        </div>
                    </div>
                    <div class="field">
                        <b>Équipe B</b>
                        <div>
                            <i class="fa-solid fa-people-group"></i>
                            <input type="text" name="teamB" id="teamB" placeholder="Entrez la classe de l'équipe B">
                        </div>
                    </div>
                    <div class="field">
                        <b>Date</b>
                        <div>
                            <i class="fa-solid fa-calendar-days"></i>
                            <input type="date" name="date" id="date" placeholder="Entrez la date du match">
                        </div>
                    </div>
                    <input type="submit" name="submit-create" id="submit-create" value="Créer">
                </form>
                <form class="statusMatch subpart" method="post">
                    <h3>Modifier un match existant</h3>
                        <div>
                        <label for="match-select"><b>Choisissez un match</b></label>
                        <select name="match-select" id="match-select">
                            <?php
                            
                            $getMatchs = $db->query('SELECT * FROM matchs WHERE status != 2 ORDER BY date ASC');
                            $getMatchs->execute();
                            
                            $matchs = $getMatchs->fetchAll();
                            
                            foreach ($matchs as $match) {
                                echo '<option value="' . $match['id'] . '">' . $match['teamA'] . ' - ' . $match['teamB'] . '</option>';
                            }
                            
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="state-select"><b>Choisissez un état</b></label>
                        <select name="state-select" id="state-select">
                            <option value="0">Planifié</option>
                            <option value="1">En cours</option>
                            <option value="3">Reporté</option>
                            <option value="4">Annulé</option>
                        </select>
                    </div>
                    <input type="submit" name="submit-edit" id="submit-edit" value="Modifier le match">
                </form>
                <form class="endMatch subpart" method="post">
                    <h3>Mettre fin à un match existant</h3>
                    <div>
                        <label for="match-select"><b>Choisissez un match</b></label>
                        <select name="match-select" id="match-select">
                            <?php
                            
                            $getPlayingMatchs = $db->query('SELECT * FROM matchs WHERE status = 1');
                            $getPlayingMatchs->execute();
                            
                            $pMatchs = $getPlayingMatchs->fetchAll();
                            
                            foreach ($pMatchs as $match) {
                                echo '<option value="' . $match['id'] . '">' . $match['teamA'] . ' - ' . $match['teamB'] . '</option>';
                            }
                            
                            ?>
                        </select>
                        <div class="field">
                            <b>Points Équipe A</b>
                            <div>
                                <i class="fa-solid fa-people-group"></i>
                                <input type="number" name="teamA" id="teamA" placeholder="Entrez les points de l'équipe A">
                            </div>
                        </div>
                        <div class="field">
                            <b>Points Équipe B</b>
                            <div>
                                <i class="fa-solid fa-people-group"></i>
                                <input type="number" name="teamB" id="teamB" placeholder="Entrez les points de l'équipe B">
                            </div>
                        </div>
                        
                    </div>
                    <input type="submit" name="submit-end" id="submit-end" value="Terminer le match">
                </form>
            </div>
        </div>
    </div>
</div>
