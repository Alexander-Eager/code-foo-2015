<?php

include("FileParser.php");
include("WordLocation.php");

class WordSearchSolver {
	// looks for a word with in the given $puzzle at the given
	//	starting position ($r, $c), moving by vector ($dRow, $dCol).
	// A word that is a single character may be returned by this function,
	//	regardless of the direction.
	// If no word matches, this returns an empty array. If there is a match, it
	//	returns an array of WordLocation objects specifying the words found
	private static function lookForWord($puzzle, $rootList, $r, $c, $dRow, $dCol) {
		// fetch variables from the arrays
		$numRows = count($puzzle);
		$numCols = count($puzzle[0]);

		// now keep going in $direction while the letter at each pos
		// could make a word
		$ans = array();
		$word = ""
		$list = $rootList;
		$row = $r; // these are the ones that will change
		$col = $c; // as we move along $direction
		while (0 <= $row && $row < $numRows
			&& 0 <= $col && $col < $numCols
			&& $list->hasLetter($puzzle[$row, $col])) {
			
			$letter = $list->getLetter($puzzle[$row][$col]);
			$word .= $letter->getCharacter();

			// check to see if we found a word
			if ($letter->isEndOfWord()) {
				$ans[] = new WordLocation($word, $r, $c, $row, $col);
				// TODO make this work
				// $rootList->deleteWord($word);
			}

			$list = $letter->getNextLettersInWords();
			$row += $dRow;
			$col += $dCol;
		}

		return $ans;
	}

	// solve the word search and return a list of WordLocations
	private static function solve($puzzle, $rootList) {
		// array of word locations
		$ans = array();

		// go through $puzzle char by char
		$numRows = count($puzzle);
		$numCols = count($puzzle[0]);
		for ($r = 0; $r < $numRows; $r ++) {
			for ($c = 0; $c < $numCols; $c ++) {
				// look in every possible direction,
				// which is specified by all combinations
				// of ($dRow, $dCol) between -1 and 1.
				for ($dRow = -1; $dRow <= 1; $dRow ++) {
					for ($dCol = -1; $dCol <= 1; $dCol ++) {
						$arr = WordSearchSolver::lookForWord(
							$puzzle, $rootList,
							$r, $c, $dRow, $dCol);
						// append $arr to $ans
						$ans = array_merge($ans, $arr);
					}
				}
			}
		}

		return $ans;
	}

	// print out the solution to the given word search
	private static function printSolution($solution) {
		// print out the word search with positions and such laid out
		echo "<div id='leftSide'>";
		echo "<pre><code>";

		// get the size of the puzzle
		$numRows = count($puzzle);
		$numCols = count($puzzle[0]);
		// here is the tricky part:
		// based on the word locations,
		// make some chars in $puzzle bold
		foreach ($solution as $wordLoc) {
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
		foreach ($solution as $wordLoc) {
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
	}

	// parse, solve and print out the solution to the given word search
	public static function solveAndPrintSolution($fileName) {
		// get the word search and words to look for from the file
		$arr = FileParser::parse($fileName);
		$puzzle = $arr[0];
		$rootList = $arr[1];

		// get the solution to the word search
		$solution = WordSearchSolver::solve($puzzle, $rootList);

		// print it out
		WordSearchSolver::printSolution($solution);
	}
}

?>