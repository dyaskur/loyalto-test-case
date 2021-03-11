<?php


echo "Jumlah pemain: ";
$totalPlayer = trim(fgets(fopen("php://stdin","r")));

echo "Jumlah dadu: ";
$totalDice = trim(fgets(fopen("php://stdin","r")));


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

if($activePlayer < 1)
{
    echo "Jumlah pemain atau dadu tidak mencukupi untuk memulai permainan ini";
}
while ($activePlayer > 1) {
    $turn++;
    //get dice number result
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
//                if ($players[$i] <= 0) {
//                    unset($players[$i]);
//                }
            } else {
                $evaluates[$player][] = $rand;
            }
        }
    }
    $turns[$turn]['result']   = $diceResults;
    $turns[$turn]['evaluate'] = $evaluates;
    $turns[$turn]['score']    = $scores;

    $activePlayer = count(array_filter($players));
}

foreach ($turns as $n => $turn) {
    echo "Giliran <b> $n</b> lempar dadu<br>";
    foreach ($players as $player => $result) {
        $score = $turn['score'][$player];
        echo "Pemain #".$player." (".$score."): ";

        if (isset($turn['result'][$player])) {
            echo implode(';', $turn['result'][$player]);
        } else {
            echo "_ (Berhenti bermain karena tidak memiliki dadu)";
        }

        echo "<br>";
    }

            echo "Setelah evaluasi<br>";

    foreach ($players as $player => $result) {

        echo "Pemain #".$player." (".$turn['score'][$player]."): ";

        if (isset($turn['evaluate'][$player])) {
            echo implode(';', $turn['evaluate'][$player]);
        } else {
            echo "_ (Berhenti bermain karena tidak memiliki dadu)";
        }
        echo "<br>";
    }
    echo "==================<br>";
}

