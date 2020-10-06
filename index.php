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
        $this->resolve();
    }

    public function resolve($tourIndex, $gameIndex)
    {
        /*

        1. select tour/game cell
        2. select first game
        3. putGameInTable (remove this first game from all table)
        4. check if all cells have at least one game
            4.1 if stoped -> select next variant from cell
                4.1.1 if no variant from cell cant be placed -> return
            4.2 if not stoped -> select ANOTHER NEXT CELL --> recursion: (1)
        5. stop if last cell has one element.



        */
        $this->table[$tourIndex][$gameIndex] = [
            'first' => $this->table[$tourIndex][$gameIndex][0]['first'],
            'second' => $this->table[$tourIndex][$gameIndex][0]['second']
        ];
        $this->putGameInTable($firstindex, $secondIndex);

        for($tour = 0; $tour < count($this->table); $tour++){
            for($game = 0; $game < count($this->table[0]); $game++){
                if(count($this->table[$tour][$games]) == 1){ //only one possible game in cell
                    continue;
                }
                if(count($this->table[$tour][$games]) == 0){ // no game to play in this cell - error
                    return false;
                }
            }
        }
        $this->resolve($tourIndex, ++$gameIndex);

    }
    function putGameInTable($first, $second)
    {
        for($tour = 0; $tour < count($this->table); $tour++){
            for($game = 0; $game < count($this->table[0]); $game++){
                foreach($this->table[$tour][$games] as &$gameVariant){
                    if($gameVariant['first'] == $first && $gameVariant['second'] == $second){
                        unset($gameVariant);
                    }
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
