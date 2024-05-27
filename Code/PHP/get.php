<?php
if (isset($_GET["match"]) && isset($_GET['id'])) {

    if ($_GET['match'] != '' && $_GET['id'] != '') {
        if ($_GET['id'] == $_SESSION['user_id']) {
            $getBet = $db-> prepare('SELECT * FROM bets WHERE player_id = ? AND match_id = ? AND status = 2');
            $getBet->execute(array($_SESSION['user_id'], $_GET['match']));

            if ($getBet->rowCount() == 1) {
                $gBet = $getBet->fetch();

                $getMatch = $db->prepare('SELECT * FROM matchs WHERE id = ?');
                $getMatch->execute(array($_GET['match']));

                if ($getMatch->rowCount() == 1) {
                    $match = $getMatch->fetch();

                    if ($match['winner'] == $gBet['choice']) {
                        switch ($match['winner']) {
                            case 0:
                                $bet = $gBet['bet'] * $match['coef_teamA'];
                                break;
                            case 1:
                                $bet = $gBet['bet'] * $match['coef_teamB'];
                                break;
                            case 2:
                                $bet = $gBet['bet'] * $match['coef_equal'];
                                break;
                            default:
                                $bet = 0;
                                break;
                        }
    
                        $updateMoney = $db->prepare('UPDATE users SET credits = ? WHERE id = ?');
                        $updateMoney->execute(array($_SESSION['user_credits'] + $bet, $_SESSION['user_id']));
                        $_SESSION['user_credits'] = $_SESSION['user_credits'] + $bet;
    
                        $updateBet = $db->prepare('UPDATE bets SET status = 3 WHERE id = ?');
                        $updateBet->execute(array($gBet['id']));
    
                        echo '<script>window.location = "index.php?page=profile"</script>';
                    }
                }
            }
        }
    }
    echo '<script>window.location = "index.php"</script>';
}



?>