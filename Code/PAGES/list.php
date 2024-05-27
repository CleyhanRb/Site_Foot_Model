<?php 

$getMatchs = $db->query("SELECT * FROM matchs ORDER BY date ASC");
$getMatchs->execute();

$matchs = $getMatchs->fetchAll();

?>


<title>Accueil - UNI</title>
<link rel="stylesheet" href="CSS/matchs.css">
<div class="container">

    <h1>Informations sur tous les matchs</h1>

    <table>
        <thead>
            <tr>
                <th>Match</th>
                <th>Score</th>
                <th>Date</th>
                <th>Heure</th>
                <!-- <th>Poule</th> -->
                <th>Etat</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            foreach ($matchs as $id => $match) {
                ?>
                <tr>
                    <td class="rTitle matchRow"><span><?= $match["teamA"] . " - " . $match["teamB"]; ?></span></td>
                    <td class="rScore matchRow"><span><?= $match["ptsA"]; ?> - <?= $match["ptsB"]; ?></span></td>
                    <td class="rDate matchRow"><span>
                        <?php
                        
                        $date = new DateTimeImmutable($match["date"]);
                        
                        echo $date->format("d/m/Y");
                        ?>
                    </span></td>
                    <td class="rHour matchRow"><span>
                        <?php
                        
                        $date = new DateTimeImmutable($match["date"]);
                        
                        echo $date->format("H:i");
                        ?>
                    </span></td>
                    <!-- <td class="rPoule matchRow"><span><?= $match["poule"]; ?></span></td> -->
                    <td class="rStatus matchRow">
                        <?php

                        switch ($match["status"]) {
                            case 0: // Plannifié
                                ?><span class="match-status match-planned">Plannifié</span></td>
                                <td class="rAction matchRow"><span>
                                <?php
                                    
                                    if (checkConnected()) {
                                        $getBets = $db->prepare("SELECT * FROM bets WHERE match_id = ? AND player_id = ?");
                                        $getBets->execute(array($match['id'], $_SESSION['user_id']));
                                    
                                        if ($getBets->rowCount() > 0) {
                                            ?>
                                            <a class="disabled" title="Vous avez déjà parié sur ce match.">Jouer</a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="?page=play&match=<?= $match["id"] ?>">Jouer</a>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <a href="?page=play&match=<?= $match["id"] ?>">Jouer</a>
                                        <?php
                                    }
                                    ?>
                                </span></td><?php
                                break;
                            case 1: // En cours
                                ?><span class="match-status match-inprogress">En cours</span></td>
                                <td class="rAction matchRow"><span>Impossible de jouer</span></td>
                                <?php
                                break;
                            case 2: // Terminé
                                ?><span class="match-status match-ended">Terminé</span></td>
                                <td class="rAction matchRow"><span><a href="index.php?page=profile">Resultats</a></span></td><?php
                                break;
                            case 3: // Reporté
                                ?><span class="match-status match-postponed">Reporté</span></td>
                                <td class="rAction matchRow"><span>Impossible de jouer</span></td><?php
                                break;
                            case 4: // Annulé
                                ?><span class="match-status match-canceled">Annulé</span></td>
                                <td class="rAction matchRow"><span>Impossible de jouer</span></td><?php
                                break;
                            default:
                                ?><span class="match-status match-unknown">Inconnu</span></td>
                                <td class="rAction matchRow"><span>Impossible de jouer</span></td><?php
                                break;

                        }

                        ?>
                </tr>
                <?php
            }
            
            ?>
        </tbody>
    </table>
    </div>