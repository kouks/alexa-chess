<?php
class ChessBot {
    protected static $moveMap = [
        "4-2" => [6,6,6,5],
    ];
    
    public static function init(ChessBoard $board, array $to) {
        $move = static::$moveMap[implode("-", $to)];
        $board->move([$move[0], $move[1]], [$move[2], $move[3]], True); 
    }
}
