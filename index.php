<?php

function dd($var){
    echo "<pre>";
    var_dump($var);die;
}
function shuffle_assoc($my_array)
{
    $keys = array_keys($my_array);
    shuffle($keys);
    foreach($keys as $key) {
        $new[$key] = $my_array[$key];
    }
    $my_array = $new;
    return $my_array;
}

class Gamers
{
    public $gamersCount = 0;

    public $couples = 0;
    public $tourCount = 0;
    public $games = [];

    public $table = [];
    public $finalTable = [];
    public $variants = [];

    public function __construct($gamersCount, $inf = 1000){
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

        $this->variants = $this->games;
        foreach ($this->variants as $key => $var) {
            unset($this->variants[$key]['used']);
        }

        $this->fillStartTable();
        $infStop = 0;
        while(!$this->resolve($this->table, 0, 0) && $infStop < $inf){
            $this->fillStartTable();
	    $infStop++;
	    if($infStop % 500 == 0){
	        echo $infStop.PHP_EOL;
	    }
        }

       echo PHP_EOL . json_encode($this->finalTable) . PHP_EOL;
    }
    public function fillStartTable()
    {
        $this->table = [];

        for($tour = 0; $tour < $this->tourCount; $tour++){
            for($game = 0; $game < $this->couples; $game++){
                if($game == 0 && $this->placeGame($game, $tour+1)){
                    $this->table[$tour][$game][] = [
                        'first' => $game,
                        'second' => $tour+1
                    ];
                } else {
                    # $this->table[$tour][$game] = $variants;
                    $this->table[$tour][$game] = shuffle_assoc($this->variants);
                }
            }
        }
    }

    public function resolve($table, $tourIndex, $gameIndex)
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

        if(
            !isset(array_values($table[$tourIndex][$gameIndex])[0])
        ){
          #  echo 'ERROR variant index out of array $tourIndex: ' . $tourIndex . ' $gameIndex: ' . $gameIndex . ' $variantIndex:' . $variantIndex;
            return false;
        }
        // select first game from variants

        $oldTable = $table;

        $firstSelectedIndex = array_values($table[$tourIndex][$gameIndex])[0]['first'];
        $secondSelectedIndex = array_values($table[$tourIndex][$gameIndex])[0]['second'];


        $table[$tourIndex][$gameIndex] = [
            'first' => $firstSelectedIndex,
            'second' => $secondSelectedIndex
        ];

        // putGameInTable (remove this first game from all table)
        $clearedTable = $this->putGameInTable($table, $tourIndex, $gameIndex, $firstSelectedIndex, $secondSelectedIndex);

        for($tour = 0; $tour < count($clearedTable); $tour++){
            for($game = 0; $game < count($clearedTable[0]); $game++){
                if(count($clearedTable[$tour][$game]) == 1){ //only one possible game in cell
                    continue;
                }
                if(count($clearedTable[$tour][$game]) == 0){ // no game to play in this cell - error
                    /*echo sprintf('ERR: firstSelectedIndex: %s, secondSelectedIndex: %s, tour: %s, game: %s',
                         $firstSelectedIndex,
                         $secondSelectedIndex,
                         $tour,
                         $game
		 ). PHP_EOL;*/
                    return false;
                }
            }
        }
        $next = $this->nextCell($tourIndex, $gameIndex);
        if(!$next){
            $this->finalTable = $clearedTable;
            return true;//finish recursion
        }
        return $this->resolve($clearedTable, $next[0], $next[1]);

    }
    public function nextCell($tourIndex, $gameIndex)
    {
        if($tourIndex >= $this->tourCount){
            return false;
        }

        if($gameIndex + 1 == $this->couples){
            $newTourIndex = ++$tourIndex;
            $newGameIndex = 0;
        } else {
            $newTourIndex = $tourIndex;
            $newGameIndex = ++$gameIndex;
        }
        if($newTourIndex >= $this->tourCount || $newGameIndex >= $this->couples){
            return false;
        } else {
            return [
                $newTourIndex,
                $newGameIndex
            ];
        }
    }
    public function putGameInTable($table, $tourIndex, $gameIndex, $first, $second)
    {
        $gamersInTour = [];
        for($game = 0; $game <= $gameIndex; $game++){//remove variants in tour
            $gamersInTour[] = $table[$tourIndex][$game]['first'];
            $gamersInTour[] = $table[$tourIndex][$game]['second'];
        }

        //remove per tour variants
        for($tour = 0; $tour < count($table); $tour++){
            for($game = 0; $game < count($table[0]); $game++){
                if($tour == $tourIndex && $game == $gameIndex){ // miss current cell
                    continue;
                }

                foreach($table[$tour][$game] as $key => $gameVariant){
                    if(
                        $gameVariant['first'] == $first &&
                        $gameVariant['second'] == $second &&
                        $tour != $tourIndex
                    ){
                        unset($table[$tour][$game][$key]);
                    }
                }
            }
        }


        for($game = $gameIndex; $game < count($table[0]); $game++){//remove variants in tour
            foreach($table[$tourIndex][$game] as $key => $value){
                if($game == $gameIndex){ // miss current cell
                    continue;
                }
                if(
                    in_array($table[$tourIndex][$game][$key]['first'], $gamersInTour) ||
                    in_array($table[$tourIndex][$game][$key]['second'], $gamersInTour)
                ){
                    unset($table[$tourIndex][$game][$key]);
                }
            }
        }
        return $table;
    }
    public function placeGame($first, $second)
    {
        if($first == $second){
            throw new \Exception('First and second same: ' . $first . ' ' .  $second );
        }
        foreach ($this->games as $key => $row) {
            if($this->games[$key]['first'] == $first && $this->games[$key]['second'] == $second){
                $this->games[$key]['used'] = true;
                return true;
            }
        }
        return false;
    }
}
$gamers = isset($argv[1]) ? $argv[1] : 4;
$iterations = isset($argv[2]) ? $argv[2] : 100000;
$game = new Gamers($gamers, $iterations);

// 13?? [[{"first":0,"second":1},{"first":7,"second":11},{"first":3,"second":4},{"first":6,"second":9},{"first":2,"second":8},{"first":5,"second":10}],[{"first":0,"second":2},{"first":1,"second":11},{"first":5,"second":9},{"first":4,"second":10},{"first":6,"second":12},{"first":7,"second":8}],[{"first":0,"second":3},{"first":6,"second":11},{"first":9,"second":10},{"first":2,"second":4},{"first":5,"second":12},{"first":1,"second":8}],[{"first":0,"second":4},{"first":3,"second":9},{"first":8,"second":11},{"first":2,"second":10},{"first":1,"second":12},{"first":6,"second":7}],[{"first":0,"second":5},{"first":9,"second":12},{"first":2,"second":7},{"first":8,"second":10},{"first":1,"second":4},{"first":3,"second":6}],[{"first":0,"second":6},{"first":1,"second":2},{"first":3,"second":8},{"first":10,"second":11},{"first":7,"second":12},{"first":4,"second":5}],[{"first":0,"second":7},{"first":5,"second":6},{"first":3,"second":11},{"first":8,"second":9},{"first":1,"second":10},{"first":4,"second":12}],[{"first":0,"second":8},{"first":9,"second":11},{"first":6,"second":10},{"first":3,"second":5},{"first":4,"second":7},{"first":2,"second":12}],[{"first":0,"second":9},{"first":1,"second":3},{"first":5,"second":7},{"first":4,"second":11},{"first":8,"second":12},{"first":2,"second":6}],[{"first":0,"second":10},{"first":4,"second":8},{"first":3,"second":7},{"first":1,"second":9},{"first":2,"second":5},{"first":11,"second":12}],[{"first":0,"second":11},{"first":4,"second":6},{"first":3,"second":12},{"first":1,"second":5},{"first":2,"second":9},{"first":7,"second":10}],[{"first":0,"second":12},{"first":3,"second":10},{"first":2,"second":11},{"first":7,"second":9},{"first":5,"second":8},{"first":1,"second":6}],[{"first":2,"second":3},{"first":4,"second":9},{"first":5,"second":11},{"first":1,"second":7},{"first":6,"second":8},{"first":10,"second":12}]]
// 14?? [[{"first":0,"second":1},{"first":4,"second":11},{"first":8,"second":10},{"first":7,"second":12},{"first":2,"second":5},{"first":3,"second":9},{"first":6,"second":13}],[{"first":0,"second":2},{"first":6,"second":9},{"first":1,"second":5},{"first":10,"second":11},{"first":3,"second":4},{"first":7,"second":13},{"first":8,"second":12}],[{"first":0,"second":3},{"first":1,"second":6},{"first":5,"second":9},{"first":4,"second":10},{"first":2,"second":7},{"first":8,"second":13},{"first":11,"second":12}],[{"first":0,"second":4},{"first":1,"second":13},{"first":2,"second":8},{"first":6,"second":11},{"first":3,"second":5},{"first":10,"second":12},{"first":7,"second":9}],[{"first":0,"second":5},{"first":4,"second":7},{"first":1,"second":8},{"first":9,"second":12},{"first":3,"second":6},{"first":2,"second":10},{"first":11,"second":13}],[{"first":0,"second":6},{"first":1,"second":10},{"first":2,"second":3},{"first":7,"second":11},{"first":9,"second":13},{"first":5,"second":12},{"first":4,"second":8}],[{"first":0,"second":7},{"first":5,"second":11},{"first":2,"second":4},{"first":6,"second":8},{"first":12,"second":13},{"first":9,"second":10},{"first":1,"second":3}],[{"first":0,"second":8},{"first":4,"second":13},{"first":1,"second":12},{"first":5,"second":7},{"first":2,"second":6},{"first":3,"second":10},{"first":9,"second":11}],[{"first":0,"second":9},{"first":1,"second":11},{"first":5,"second":10},{"first":3,"second":12},{"first":7,"second":8},{"first":4,"second":6},{"first":2,"second":13}],[{"first":0,"second":10},{"first":4,"second":12},{"first":1,"second":9},{"first":3,"second":8},{"first":2,"second":11},{"first":6,"second":7},{"first":5,"second":13}],[{"first":0,"second":11},{"first":7,"second":10},{"first":4,"second":5},{"first":8,"second":9},{"first":3,"second":13},{"first":6,"second":12},{"first":1,"second":2}],[{"first":0,"second":12},{"first":5,"second":6},{"first":1,"second":4},{"first":8,"second":11},{"first":10,"second":13},{"first":2,"second":9},{"first":3,"second":7}],[{"first":0,"second":13},{"first":1,"second":7},{"first":6,"second":10},{"first":3,"second":11},{"first":4,"second":9},{"first":2,"second":12},{"first":5,"second":8}]]
// 15?? [[{"first":0,"second":1},{"first":2,"second":7},{"first":6,"second":10},{"first":3,"second":12},{"first":8,"second":14},{"first":4,"second":13},{"first":5,"second":9}],[{"first":0,"second":2},{"first":3,"second":8},{"first":4,"second":12},{"first":1,"second":11},{"first":9,"second":14},{"first":6,"second":13},{"first":7,"second":10}],[{"first":0,"second":3},{"first":2,"second":10},{"first":1,"second":12},{"first":4,"second":5},{"first":6,"second":8},{"first":11,"second":13},{"first":7,"second":9}],[{"first":0,"second":4},{"first":8,"second":13},{"first":5,"second":10},{"first":6,"second":12},{"first":9,"second":11},{"first":7,"second":14},{"first":2,"second":3}],[{"first":0,"second":5},{"first":9,"second":12},{"first":3,"second":10},{"first":1,"second":7},{"first":4,"second":6},{"first":8,"second":11},{"first":2,"second":14}],[{"first":0,"second":6},{"first":1,"second":8},{"first":3,"second":4},{"first":11,"second":14},{"first":12,"second":13},{"first":5,"second":7},{"first":9,"second":10}],[{"first":0,"second":7},{"first":1,"second":5},{"first":2,"second":8},{"first":10,"second":13},{"first":3,"second":11},{"first":4,"second":14},{"first":6,"second":9}],[{"first":0,"second":8},{"first":12,"second":14},{"first":10,"second":11},{"first":4,"second":7},{"first":1,"second":2},{"first":3,"second":13},{"first":5,"second":6}],[{"first":0,"second":9},{"first":1,"second":4},{"first":3,"second":14},{"first":2,"second":11},{"first":8,"second":12},{"first":6,"second":7},{"first":5,"second":13}],[{"first":0,"second":10},{"first":2,"second":13},{"first":3,"second":5},{"first":7,"second":12},{"first":1,"second":14},{"first":4,"second":9},{"first":6,"second":11}],[{"first":0,"second":11},{"first":7,"second":8},{"first":5,"second":14},{"first":1,"second":6},{"first":2,"second":4},{"first":10,"second":12},{"first":9,"second":13}],[{"first":0,"second":12},{"first":4,"second":11},{"first":13,"second":14},{"first":3,"second":7},{"first":1,"second":10},{"first":2,"second":5},{"first":8,"second":9}],[{"first":0,"second":13},{"first":8,"second":10},{"first":6,"second":14},{"first":5,"second":12},{"first":2,"second":9},{"first":1,"second":3},{"first":7,"second":11}],[{"first":0,"second":14},{"first":4,"second":10},{"first":5,"second":8},{"first":1,"second":13},{"first":11,"second":12},{"first":3,"second":9},{"first":2,"second":6}],[{"first":1,"second":9},{"first":7,"second":13},{"first":4,"second":8},{"first":3,"second":6},{"first":2,"second":12},{"first":5,"second":11},{"first":10,"second":14}]]
