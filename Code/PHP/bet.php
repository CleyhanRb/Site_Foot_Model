<?php
if (isset($_POST["bChoice"]) && isset($_POST['bet']) && isset($_SESSION['match_id'])) {
    $bChoice = intval($_POST["bChoice"]);
    $bet = abs(intval($_POST['bet']));

    if ($bChoice != '' && !empty($bet)) {
        $addBet = $db->prepare("INSERT INTO bets(player_id,match_id,choice,bet) VALUES(:player_id,:match_id,:choice,:bet)");
        $addBet->execute([
            'player_id' => htmlspecialchars($_SESSION['user_id']),
            'match_id' => htmlspecialchars($_SESSION['match_id']),
            'choice' => htmlspecialchars($bChoice),
            'bet' => htmlspecialchars($bet)
        ]);

        $updateMoney = $db->prepare('UPDATE users SET credits = ? WHERE id = ?');
        $updateMoney->execute(array($_SESSION['user_credits'] - $bet, $_SESSION['user_id']));
        $_SESSION['user_credits'] = $_SESSION['user_credits'] - $bet;

        echo '<script>window.location = "index.php"</script>';
    }
}



?>
