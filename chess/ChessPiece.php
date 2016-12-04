<?php
	

class ChessPiece {
	/**
	 * Indicates which played piece does belong to
	 * Either string(w) or string(b)
	 *
	 * @var color
	 */
	public $color;

	/**
	 * Coordinates
	 * $position->col
	 * $position->row
	 *
	 * @var position array
	 */
	public $position;

	/**
	 * Piece type
	 *
	 * @var rank
	 */
	public $rank;

	public function __construct($color, $rank, $position)
	{
		$this->color = $color;

		$this->rank = $rank;

		$this->position = (object) [
			"col" => $position[0],
			"row" => $position[1]
		];
	}

	public function getRank() {
		return $this->rank;
	}

	public function getRow() {
		return $this->position->row;
	}

	public function getCol() {
		return $this->position->col;
	}

	public function getColor() {
		return $this->color;
	}

	public function setPos($row, $col) {
		$this->position = (object) [
			"col" => $col,
			"row" => $row
		];
		return $this;
	}
}

class ChessRank {
	const PAWN = "P";
	const BISHOP = "B";
	const KNIGHT = "K";
	const ROOK = "R";
	const QUEEN = "Q";
	const KING = "K";
}