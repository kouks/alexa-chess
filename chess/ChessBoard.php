<?php
	
class ChessBoard {
	/**
	 * Holds up the whole chessboard array
	 *
	 * @var $board arrayof string
	 */
	public $pieces = [];
	
	public function __construct($chessboard)
	{
		if($chessboard) {
			$this->loadBoard($chessboard);
		} else {
			$this->newGame();
		}
	}

	public function loadBoard($chessboard) {
		foreach ($chessboard as $row => $squares) {
			foreach ($squares as $col => $square) {
				if ($square == "x") {
					continue;
				}

				$piece = new ChessPiece(
					substr($square, 0, 1),
					substr($square, 1, 2),
					[$col, $row]
				);

				$this->pieces[$row . "." . $col] = $piece;
			}
		}
	}

	public function newGame() {
		$chessboard = 
		[
			["wR", "wN", "wB", "wQ", "wK", "wB", "wN", "wR"],
			["wP", "wP", "wP", "wP", "wP", "wP", "wP", "wP"],
			["x", "x", "x", "x", "x", "x", "x", "x"],
			["x", "x", "x", "x", "x", "x", "x", "x"],
			["x", "x", "x", "x", "x", "x", "x", "x"],
			["x", "x", "x", "x", "x", "x", "x", "x"],
			["bP", "bP", "bP", "bP", "bP", "bP", "bP", "bP"],
			["bR", "bN", "bB", "bQ", "bK", "bB", "bN", "bR"],
		];

		$this->loadBoard($chessboard);
	}

	public function toArray() {
		$array = array_fill(0, 8, array_fill(0, 8, 'x'));

		foreach ($this->pieces as $piece) {
			$array
				[$piece->getRow()]
				[$piece->getCol()] = $piece->getColor() . $piece->getRank();
		}

		return $array;
	}

	public function toJson($reverse = false)
	{
		$chessboard = $this->toArray();

		if ($reverse) {
			$chessboard = array_reverse($chessboard);
		}

		return json_encode($chessboard);
	}

	public function save() {
		unlink("chess.txt");
		file_put_contents("chess.txt", $this->toJson());
	}

	public function getPieceOn($row, $col) {
		if (isset($this->pieces[$row . "." . $col])) {
			return $this->pieces[$row . "." . $col];
		}

		return false;
	}

	public function move($from, $to, $botMove=False) {
		if(!is_numeric($from[0]) || !is_numeric($from[1]) || !is_numeric($to[0]) || !is_numeric($to[1])) {
			file_put_contents("err.txt", "invalid input: " . json_encode($from) . "|" . json_encode($to));
			die();
		}


		$piece = $this->getPieceOn($from[1], $from[0]);

		if ($piece) {
			$piece->setPos($to[1], $to[0]);

			$target = $this->getPieceOn($to[1], $to[0]);
			
			if($target) {
				$this->deletePiece($to[1], $to[0]);
			}
		}
		if (!$botMove) {
			ChessBot::init($this, $to);
		}
	}

	public function deletePiece($row, $col) {
		unset($this->pieces[$row . "." . $col]);

		return $this;
	}
}
