<?php

include("FileParser.php");
include("WordLocation.php");

// solve the word search and return a list of WordLocations
function solve($puzzle, $rootList) {
	// array of word locations
	$ans = array();

	// go through $puzzle char by char
	$numRows = count($puzzle);
	$numCols = count($puzzle[0]);
	for ($r = 0; $r < $numRows; $r ++) {
		for ($c = 0; $c < $numCols; $c ++) {
			// if the letter at ($r, $c) could be the start of a word
			if ($rootList->hasLetter($puzzle[$r][$c])) {
				// get that letter and start forming the potential word
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$word = $letter->getCharacter();

				// check for one-letter word
				if ($letter->isEndOfWord()) {
					$ans[] = new WordLocation($word, $r, $c, $r, $c);
				}

				// check going upward
				$k = 1;
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($r - $k >= 0 && $list->hasLetter($puzzle[$r - $k][$c])) {


					$letter = $list->getLetter($puzzle[$r - $k][$c]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r - $k, $c);
					}
					$k ++;
				}

				// check going downward
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($r + $k < $numRows && $list->hasLetter($puzzle[$r + $k][$c])) {

					$letter = $list->getLetter($puzzle[$r + $k][$c]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r + $k, $c);
					}
					$k ++;
				}

				// check going left
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c - $k >= 0 && $list->hasLetter($puzzle[$r][$c - $k])) {

					$letter = $list->getLetter($puzzle[$r][$c - $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r, $c - $k);
					}
					$k ++;
				}

				// check going right
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c + $k < $numCols && $list->hasLetter($puzzle[$r][$c + $k])) {

					$letter = $list->getLetter($puzzle[$r][$c + $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r, $c + $k);
					}
					$k ++;
				}

				// check going up-left
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c - $k >= 0 && $r - $k >= 0
						&& $list->hasLetter($puzzle[$r - $k][$c - $k])) {

					$letter = $list->getLetter($puzzle[$r - $k][$c - $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r - $k, $c - $k);
					}
					$k ++;
				}

				// check going up-right
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c + $k < $numCols && $r - $k >= 0
						&& $list->hasLetter($puzzle[$r - $k][$c + $k])) {

					$letter = $list->getLetter($puzzle[$r - $k][$c + $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r - $k, $c + $k);
					}
					$k ++;
				}

				// check going down-right
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c + $k < $numCols && $r + $k < $numRows
						&& $list->hasLetter($puzzle[$r + $k][$c + $k])) {

					$letter = $list->getLetter($puzzle[$r + $k][$c + $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r + $k, $c + $k);
					}
					$k ++;
				}

				// check going down-left
				$k = 1;
				$letter = $rootList->getLetter($puzzle[$r][$c]);
				$list = $letter->getNextLettersInWords();
				$word = $letter->getCharacter();

				while ($c - $k >= 0 && $r + $k < $numRows
						&& $list->hasLetter($puzzle[$r + $k][$c - $k])) {

					$letter = $list->getLetter($puzzle[$r + $k][$c - $k]);
					$list = $letter->getNextLettersInWords();
					$word = $word . $letter->getCharacter();
					if ($letter->isEndOfWord()) {
						$ans[] = new WordLocation($word, $r, $c, $r + $k, $c - $k);
					}
					$k ++;
				}
			}
		}
	}

	return $ans;
}

// parse, solve and print out the solution to the given word search
function solveAndPrintSolution($fileName) {
	// get the word search and words to look for from the file
	$arr = FileParser::parse($fileName);
	$puzzle = $arr[0];
	$rootList = $arr[1];

	// get the solution to the word search
	$ans = solve($puzzle, $rootList);

	// print out the word search with positions and such laid out
	echo "<div id='leftSide'>";
	echo "<pre><code>";

	// get the size of the puzzle
	$numRows = count($puzzle);
	$numCols = count($puzzle[0]);
	// here is the tricky part:
	// based on the word locations,
	// make some chars in $puzzle bold
	foreach ($ans as $wordLoc) {
		// figure out start point and dx, dy
		$r = $wordLoc->getStartRow();
		$c = $wordLoc->getStartCol();
		$dy = 0;
		if ($wordLoc->getEndRow() > $r) {
			$dy = 1;
		} else if ($wordLoc->getEndRow() < $r) {
			$dy = -1;
		}
		$dx = 0;
		if ($wordLoc->getEndCol() > $c) {
			$dx = 1;
		} else if ($wordLoc->getEndCol() < $c) {
			$dx = -1;
		}
		// move in that direction and add <em> tags
		while ($r != $wordLoc->getEndRow()
				|| $c != $wordLoc->getEndCol()) {
			// may add excess <em> tags, but that's ok
			$puzzle[$r][$c] = "<strong>" . $puzzle[$r][$c] . "</strong>";
			$r += $dy;
			$c += $dx;
		}
		// get the last char
		$puzzle[$r][$c] = "<strong>" . $puzzle[$r][$c] . "</strong>";
	}
	// print out the header for the columns,
	// e.g.  | A B C D E F
	echo "   |";
	$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for ($i = 0; $i < $numCols; $i ++) {
		echo " " . $alph[$i];
	}
	// print out the separating line
	echo "\n---";
	for ($i = 0; $i < $numCols; $i ++) {
		echo "--";
	}
	echo "-\n";
	// print out the actual word search, with numbers on the left
	for ($r = 0; $r < $numRows; $r ++) {
		echo "" . ($r + 1) . " ";
		if ($r + 1 < 10) {
			echo " ";
		}
		echo "|";
		for ($c = 0; $c < $numCols; $c ++) {
			echo " " . $puzzle[$r][$c];
		}
		echo "\n";
	}
	echo "</code></pre>";
	echo "</div>";

	// print out the solution in a table.
	echo "<div id='rightSide'>";
	echo "<table style='width:100%'>";
	echo "<tr>";
	echo "<th>Word</th>";
	echo "<th>Start Location</th>";
	echo "<th>End Location</th>";
	echo "<th>Direction</th>";
	echo "</tr>";
	foreach ($ans as $wordLoc) {
		echo "<tr>";
		echo "<td>" . $wordLoc->getWord() . "</td>";
		echo "<td>" . $wordLoc->getStartLoc() . "</td>";
		echo "<td>" . $wordLoc->getEndLoc() . "</td>";
		echo "<td>" . $wordLoc->getDirection() . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
}

?>