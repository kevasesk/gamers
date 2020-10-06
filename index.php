<?php

class Gamers {
    public $gamersCount = 0;

    public $couples = 0;
    public $tourCount = 0;
    public $games = [];

    Public $table = [];

    public function __construct($gamersCount){
        $this->gamersCount = $gamersCount;
        $this->couples = (int)($gamersCount / 2);
        for($i=0;$i<$gamersCount;$i++){
            for($j=$i+1;$j<$gamersCount;$j++){
                $this->games[] = [
                    'first' => $i,
                    'second' => $j,
                    'used' => false
                ];
            }
        }
        $this->tourCount = ceil(count($this->games) / $this->couples);

            //TODO

        $variants = $this->games;
        foreach ($variants as &$var) {
            unset($var['used']);
        }

        for($tour = 0; $tour < $this->tourCount; $tour++){
            for($game = 0; $game < $this->couples; $game++){
                if($game == 0 && $this->placeGame($game, $tour+1)){
                    $this->table[$tour][$game][] = [
                        'first' => $game,
                        'second' => $tour+1
                    ];
                } else {
                    $this->table[$tour][$game] = $variants;
                }

            }
        }

    }

    function placeGame($first, $second)
    {
        if($first == $second){
            throw new \Exception('First and second same: ' . $first . ' ' .  $second );
        }
        foreach ($this->games as &$row) {
            if($row['first'] == $first && $row['second'] == $second){
                $row['used'] = true;
                return true;
            }
        }
        return false;
    }
    function printTable()
    {
        $html = '<table>';

            for($game = 0; $game < count($this->table[0]); $game++){
                $html .= '<tr>';
                for($tour = 0; $tour < count($this->table); $tour++){
                    $html .= '<td style="border: solid 1px black;">' .
                    (count($this->table[$tour][$game]) == 1 ?
                     $this->table[$tour][$game][0]['first'] . ' ' . $this->table[$tour][$game][0]['second'] :
                      '[]') .
                     '</td>';
                }
                $html .= '</tr>';
            }

        $html .= '</table>';
        return $html;
    }
}

$game = new Gamers(5);
echo $game->printTable();
#var_dump($game->games);
