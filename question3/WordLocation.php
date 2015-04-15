<?php

// represents the location of a word in a grid
// which is just the start and end points, and
// the direction of travel
class WordLocation {
	// the word
	private $word = "";
	// the starting location, of the form LetterNumber, e.g. B6
	private $startLoc = "";
	private $startRow = 0;
	private $startCol = 0;
	// the ending location, of the form LetterNumber, e.g. B6
	private $endLoc = "";
	private $endRow = 0;
	private $endCol = 0;
	// the direction the word goes in, e.g. Up-Left
	private $direction = "";

	public function __construct($word, $startRow, $startCol, $endRow, $endCol) {
		$this->word = $word;
		$this->startRow = $startRow;
		$this->startCol = $startCol;
		$this->endRow = $endRow;
		$this->endCol = $endCol;
		// convert locations to strings
		$startRow ++;
		$endRow ++;
		$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$this->startLoc = $alph[$startCol] . "$startRow";
		$this->endLoc = $alph[$endCol] . "$endRow";
		// determine direction from change in position
		$dx = $endCol - $startCol;
		$dy = $endRow - $startRow;
		if ($dx == 0 && $dy == 0) {
			$this->direction = "In Place";
		} else if ($dx < 0 && $dy == 0) {
			$this->direction = "Left";
		} else if ($dx > 0 && $dy == 0) {
			$this->direction = "Right";
		} else if ($dx == 0 && $dy < 0) {
			$this->direction = "Up";
		} else if ($dx == 0 && $dy > 0) {
			$this->direction = "Down";
		} else if ($dx < 0 && $dy < 0) {
			$this->direction = "Up-Left";
		} else if ($dx < 0 && $dy > 0) {
			$this->direction = "Down-Left";
		} else if ($dx > 0 && $dy < 0) {
			$this->direction = "Up-Right";
		} else if ($dx > 0 && $dy > 0) {
			$this->direction = "Down-Right";
		}
	}

	public function getWord() {
		return $this->word;
	}

	public function getStartLoc() {
		return $this->startLoc;
	}

	public function getStartRow() {
		return $this->startRow;
	}

	public function getStartCol() {
		return $this->startCol;
	}

	public function getEndLoc() {
		return $this->endLoc;
	}

	public function getEndRow() {
		return $this->endRow;
	}

	public function getEndCol() {
		return $this->endCol;
	}

	public function getDirection() {
		return $this->direction;
	}
}

?>