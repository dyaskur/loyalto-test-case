<?php


echo "Jumlah pemain: ";
$totalPlayer = trim(fgets(fopen("php://stdin", "r")));

echo "Jumlah dadu: ";
$totalDice = trim(fgets(fopen("php://stdin", "r")));


$arr       = [];
$scores    = [];
$turns     = [];
$evaluates = [];
$players   = [];
//set default dice count each player
for ($i = 1; $i <= $totalPlayer; $i++) {
    $players[$i] = $totalDice;
}
$turn = 0;
//init player score
for ($i = 1; $i <= $totalPlayer; $i++) {
    $scores[$i] = 0;
}

$activePlayer = count(array_filter($players));

if ($activePlayer < 1) {
    echo "Jumlah pemain atau dadu tidak mencukupi untuk memulai permainan ini";
}
while ($activePlayer > 1) {
    $turn++;
    //get dice number result
    $oldScores   = $scores;
    $diceResults = [];
    $evaluates   = [];
    foreach ($players as $player => $dice) {
        for ($i = 1; $i <= $dice; $i++) {
            $rand                   = rand(1, 6);
            $diceResults[$player][] = $rand;

            if ($rand == 6) {
                $scores[$player]  = $scores[$player] + 1;
                $players[$player] = $dice - 1;
            } elseif ($rand == 1) {
                if (isset($players[$player + 1])) {
                    $evaluates[$player + 1][] = 1;
                    $players[$player + 1]++;
                } else {
                    $evaluates[1][] = 1;
                    $players[1]++;
                }
                $players[$player] = $dice - 1;
            } else {
                $evaluates[$player][] = $rand;
            }
        }
    }
    $turns[$turn]['result']   = $diceResults;
    $turns[$turn]['evaluate'] = $evaluates;
    $turns[$turn]['score']    = $scores;

    echo "Giliran  $turn lempar dadu\n";
    foreach ($players as $player => $result) {
        $score = $oldScores[$player];
        echo "Pemain #".$player." (".$score."): ";

        if (isset($turn['result'][$player])) {
            echo implode(';', $diceResults[$player]);
        } else {
            echo "_ (Berhenti bermain karena tidak memiliki dadu)";
        }

        echo "\n";
    }

    echo "Setelah evaluasi\n";

    foreach ($players as $player => $result) {
        echo "Pemain #".$player." (".$scores[$player]."): ";

        if (isset($evaluates[$player])) {
            echo implode(';', $evaluates[$player]);
        } else {
            echo "_ (Berhenti bermain karena tidak memiliki dadu)";
        }
        echo "\n";
    }
    echo "==================\n";
    $activePlayer = count(array_filter($players));
    if ($activePlayer === 1) {
        $lastMan = array_keys($players, max($players))[0];
        echo "Game berakhir karena hanya pemain #".$lastMan." yang memiliki dadu\n";
        $winner = array_keys($scores, max($scores))[0];
        echo "Game dimenangkan oleh pemain #".$winner." memiliki poin lebih banyak dari pemain lainnya.\n";
    }
}