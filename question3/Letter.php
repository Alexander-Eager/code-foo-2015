<?php

// represents a letter in the forest described in README.md
class Letter {
	// the character that this Letter represents
	private $character = '';
	// true if this character is the end of a word,
	// false otherwise
	private $isEnd = false;
	// the list of characters that could follow this
	// one in the formation of a valid word
	private $nextLettersInWords = null;

	// make a Letter that represents $char with LetterList $list
	public function __construct($char, $list) {
		$this->character = $char;
		$this->nextLettersInWords = $list;
	}

	public function getCharacter() {
		return $this->character;
	}

	public function isEndOfWord() {
		return $this->isEnd;
	}
	public function setIsEndOfWord($bool) {
		$this->isEnd = $bool;
	}

	public function getNextLettersInWords() {
		return $this->nextLettersInWords;
	}
}

?>