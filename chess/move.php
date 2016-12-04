<?php

require 'require.php';

$rawJson = file_get_contents('php://input');

$result = json_decode($rawJson);

file_put_contents("debug.txt", $rawJson);

$file = file_get_contents("chess.txt");

$board = strlen($file) > 0 ? json_decode($file) : false;

$chessboard = new ChessBoard($board);

$chessboard->move($result->from, $result->to);

$chessboard->save();
