<?php

include("LetterList.php");

class FileParser {
	// returns an array with the first element as
	// the word search double array (really array of strings) and the
	// second as the root LetterList.
	public static function parse($fileName) {
		// initial file work and such
		$fileHandle = fopen($fileName, 'r');
		$puzzle = array();
		$rootList = new LetterList();

		// get every line until you hit a new line, strip spaces,
		// and add that string to the puzzle as the next row
		$currLine;
		while (strlen($currLine = fgets($fileHandle)) != 1) {
			$currLine = str_replace(" ", "", trim($currLine));
			$puzzle[] = str_split($currLine);
		}

		// skip over 3 lines we do not care about
		for ($i = 0; $i < 3; $i ++) {
			fgets($fileHandle);
		}

		// now each line is a word to add to our $rootList
		while (($currLine = fgets($fileHandle)) !== false) {
			$rootList->addWord(trim($currLine));
		}

		// close and return
		fclose($fileHandle);
		return array($puzzle, $rootList);
	}
}

?>