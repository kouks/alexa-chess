<?php

header('Access-Control-Allow-Origin: *');

require 'require.php';

$file = file_get_contents("chess.txt");

$board = strlen($file) > 0 ? json_decode($file) : false;

$chessboard = new ChessBoard($board);

echo $chessboard->toJson(true);
