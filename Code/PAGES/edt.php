<?php 

function AddWeeks(){
    global $db;
    $addWeek = $db->prepare("INSERT INTO weeks( start, end) VALUES (?,?)");

    $dates = [
        "2024-03-18",
        "2024-03-25",
        "2024-04-01",
        "2024-04-22",
        "2024-04-29",
        "2024-05-06",
        "2024-05-13",
        "2024-05-20",
        "2024-05-27",
    ];
    foreach ($dates as $date) {
        $dateD = new DateTime($date);
        $dateD = $dateD->modify('+6 day');
        
        $addWeek->execute(array($date, $dateD->format("Y-m-d")));
    }

}

$getEDT = $db->query("SELECT * FROM weeks ORDER BY id ASC");
$getEDT->execute();

$EDT = $getEDT->fetchAll();


?>

<title>Emploi du temps - UNI</title>
<link rel="stylesheet" href="CSS/edt.css">
<div class="container">
    <table>
        <thead>
            <tr>
                <th colspan="1" rowspan="2">Semaine</th>
                <th colspan="2">Lundi</th>
                <th colspan="2">Mardi</th>
                <th colspan="2">Mercredi</th>
                <th colspan="2">Jeudi</th>
                <th colspan="2">Vendredi</th>
            </tr>
            <tr>
                <th>12h30</th>
                <th>13h</th>
                <th>12h30</th>
                <th>13h</th>
                <th>12h30</th>
                <th>13h</th>
                <th>12h30</th>
                <th>13h</th>
                <th>12h30</th>
                <th>13h</th>
            </tr>
        </thead>
        <tbody>
                        <!-- 
            
            <td>25 au 31 Mars</td>
            <td>01 au 07 Avril</td>
            <td>22 au 28 Avril</td>
            <td>29 au 05 Mai</td>
            <td>06 au 12 Mai</td>
            <td>13 au 19 Mai</td>
            <td>20 au 26 Mai</td>
            <td>27 au 02 Juin</td> 
        -->

            <?php
            $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
            $formatter->setPattern('d/MM');
            
            foreach ($EDT as $week) {


                $startD = new DateTimeImmutable($week["start"]);
                $start = $formatter->format($startD);

                $endD = new DateTimeImmutable($week["end"]);
                $end = $formatter->format($endD);
                
                
                $weekScore = []

                ?>

                <tr>
                    <!-- Semaine -->
                    <td class="matchTitle">Du <?= $start; ?> au <?= $end; ?></td>

                    <?php
                    $date = DateTime::createFromImmutable($startD);
                    $date = $date->modify('-1 day');
                    
                    for ($i=0; $i < 5; $i+=1) { 
                        $dateD = $date->modify('+1 day');
                        
                        $dayMatch = $db->prepare("SELECT * FROM matchs WHERE date = :date");
                        $dayMatch->execute(["date" => $dateD->format("Y-m-d") . " 12:30:00"]);
 

                        if ($dayMatch->rowCount() == 1) {
                            $match = $dayMatch->fetchAll()[0];
                            ?>
                            
                            <td class="d<?= $i; ?> dayn day first"><?= $match["teamA"] . " - " . $match["teamB"]; ?></td>
                            <?php

                            array_push($weekScore, $match['ptsA'] . " - " . $match['ptsB']);

                        }else{
                            ?>
                            
                            <td class="d<?= $i; ?> dayn day first"> - </td>
                            <?php
                            array_push($weekScore, " - ");
                        }


                        $dayMatch = $db->prepare("SELECT * FROM matchs WHERE date = :date");
                        $dayMatch->execute(["date" => $dateD->format("Y-m-d") . " 13:00:00"]);
 

                        if ($dayMatch->rowCount() == 1) {
                            $match = $dayMatch->fetchAll()[0];
                            ?>
                            
                            <td class="dayn"><?= $match["teamA"] . " - " . $match["teamB"]; ?></td>
                            <?php

                            array_push($weekScore, $match['ptsA'] . " - " . $match['ptsB']);

                        }else{
                            ?>
                            
                            <td class="dayn"> - </td>
                            <?php
                            array_push($weekScore, " - ");
                        }

                    }
                    ?>
                </tr>
                <tr>
                    <!-- Semaine -->
                    <td class="matchTitle">Scores</td>
                    
                    <?php
                    
                    for ($i=0; $i < count($weekScore); $i+=2) { 
                        ?>
                        <td class="d<?= $i; ?>s score first"><?= $weekScore[$i]; ?></td>
                        <td class="d<?= $i; ?>s"><?= $weekScore[$i+1]; ?></td>
                        <?php
                    }
                    ?>
                </tr>

                <?php
            }
            
            
            ?>
            
        </tbody>
    </table>
</div>