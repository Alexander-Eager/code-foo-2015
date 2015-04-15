<?php

include("Letter.php");

// this is a list of Letters, which in turn have LetterLists
// so basically this is a list of children for a Letter,
// or a forest of Letters. See README.md in the question2/
// folder for information on how this is used
class LetterList {
	// list of letters that this wraps around
	// implemented as map from char => Letter
	private $listOfLetters = array();

	// make a new letter list
	public function __construct() {
		// does nothing
	}

	// determine if the given character is in our letter list
	public function hasLetter($char) {
		return array_key_exists($char, $this->listOfLetters);
	}

	// get the Letter for a given character
	public function getLetter($char) {
		return $this->listOfLetters[$char];
	}

	// add a word to this LetterList
	public function addWord($word) {
		// add the character if it isn't already there
		if (!$this->hasLetter($word[0])) {
			$this->listOfLetters[$word[0]] = new Letter($word[0], new LetterList());
		}
		$letter = $this->getLetter($word[0]);
		// base case: this character is the last one in $word
		if (strlen(utf8_decode($word)) == 1) {
			$letter->setIsEndOfWord(true);
		} else {
			// add word minus the first character to $letter's tree
			$letter->getNextLettersInWords()->addWord(substr($word, 1));
		}
	}
}

?>