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

	// remove a word from this LetterList
	// returns true if the Letter for the last character
	//	in $word was removed from the tree, false otherwise
	public function deleteWord($word) {
		// if the character is not there, return false
		if (!$this->hasLetter($word[0])) {
			return false;
		}

		$letter = $this->getLetter($word[0]);

		// if we are on the last character in $word,
		//	change $letter->isEndOfWord(), and look
		//	at removing this letter
		if (strlen(utf8_decode($word)) == 1) {
			$letter->setIsEndOfWord(false);
			// no children => remove this letter
			if (count($letter->getNextLettersInWords()) == 0) {
				unset($listOfLetters[$letter->getCharacter()]);
				return true;
			}
			return false;
		} else {
			$ans = $letter->getNextLettersInWords()
				->deleteWord(substr($word, 1));

			// no children and not end of word => remove this letter
			if (count($letter->getNextLettersInWords()) == 0
				&& !$letter->isEndOfWord()) {
				unset($listOfLetters[$letter->getCharacter()]);
			}
			return $ans;
		}
	}
}

?>