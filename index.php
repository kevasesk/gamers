<?php

function dd($var){
    echo "<pre>";
    var_dump($var);die;
}
class Gamers
{
    public $gamersCount = 0;

    public $couples = 0;
    public $tourCount = 0;
    public $games = [];

    public $table = [];
    public $finalTable = [];

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
        foreach ($variants as $key => $var) {
            unset($variants[$key]['used']);
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
        $table = $this->table;
        $this->resolve($table, 0, 0, 0);
    }

    public function resolve($table, $tourIndex, $gameIndex, $variantIndex)
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
            !isset(array_values($table[$tourIndex][$gameIndex])[$variantIndex])
        ){
            echo 'ERROR variant index out of array $tourIndex: ' . $tourIndex . ' $gameIndex: ' . $gameIndex . ' $variantIndex:' . $variantIndex;
            return false;
        }
        // select first game from variants

        $oldTable = $table;

        $firstSelectedIndex = array_values($table[$tourIndex][$gameIndex])[$variantIndex]['first'];
        $secondSelectedIndex = array_values($table[$tourIndex][$gameIndex])[$variantIndex]['second'];


        $table[$tourIndex][$gameIndex] = [
            'first' => $firstSelectedIndex,
            'second' => $secondSelectedIndex
        ];

        // putGameInTable (remove this first game from all table)
        $clearedTable = $this->putGameInTable($table, $tourIndex, $gameIndex, $firstSelectedIndex, $secondSelectedIndex, $variantIndex);

        for($tour = 0; $tour < count($clearedTable); $tour++){
            for($game = 0; $game < count($clearedTable[0]); $game++){
                if(count($clearedTable[$tour][$game]) == 1){ //only one possible game in cell
                    continue;
                }
                if(count($clearedTable[$tour][$game]) == 0){ // no game to play in this cell - error
                    echo sprintf('ERR: firstSelectedIndex: %s, secondSelectedIndex: %s, variantIndex: %s, tour: %s, game: %s',
                         $firstSelectedIndex,
                         $secondSelectedIndex,
                         $variantIndex,
                         $tour,
                         $game
                    ). PHP_EOL;
                    return false;
                }
            }
        }
        $variantIndex = 0;
        $next = $this->nextCell($tourIndex, $gameIndex);
        if(!$next){
            $this->finalTable = $clearedTable;
            return true;//finish recursion
        }
        $k = 0;
        while($this->resolve($clearedTable, $next[0], $next[1], $variantIndex) === false && $k < 10){
            $k++;
            ++$variantIndex;
            $this->resolve($oldTable, $tourIndex, $gameIndex, $variantIndex);
            // need recursive go back
        }


//        if($this->resolve($newTable, $tourIndex, $gameIndex)){
//
//        } else {
//            $gameIndex++;
//            if($gameIndex + 1 >= $this->couples){
//                return false;
//            }
//            $this->resolve($newTable, $tourIndex, $gameIndex);
//        }

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
    public function putGameInTable($table, $tourIndex, $gameIndex, $first, $second, $variantIndex)
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
                        #echo $tour .' '.$game.' was unset' . PHP_EOL;
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
                    #echo $tourIndex .' '.$game.' was unset in tour' . PHP_EOL;
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
    public function printTable()
    {
        $html = '<table>';
            for($game = 0; $game < count($this->finalTable[0]); $game++){
                $html .= '<tr>';
                for($tour = 0; $tour < count($this->finalTable); $tour++){
                    $html .= '<td style="border: solid 1px black;">' .
                    (count($this->finalTable[$tour][$game]) == 1 ?
                     $this->finalTable[$tour][$game][0]['first'] . ' ' . $this->finalTable[$tour][$game][0]['second'] :
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
echo "<pre>";
for($tour = 0; $tour < count($game->finalTable); $tour++) {
    for ($gameIndex = 0; $gameIndex < count($game->finalTable[0]); $gameIndex++) {
        echo sprintf('%s %s : %s', $tour, $gameIndex, print_r($game->finalTable[$tour][$gameIndex], true));
    }
    echo PHP_EOL . PHP_EOL . PHP_EOL;
}
