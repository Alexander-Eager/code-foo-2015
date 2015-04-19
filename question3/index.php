<!DOCTYPE html>
<html>

<head>
	<title>IGN Code-Foo Question 3</title>
	<style>
		#leftSide {
			width: 50%;
			float: left;
			text-align: left;
		}
		#rightSide {
			width: 50%;
			float: right;
			text-align: center;
		}
		code {
			font-family: monospace;
			font-size: 1.5em;
		}
		strong {
			font-weight: bold;
			color: blue;
		}
	</style>
</head>

<body>

	<h1>IGN Code-Foo Question 3 Solution</h1>
	<h2>Note: makes use of PHP, so use Apache or the like.</h2>

	<?php
		include("WordSearchSolver.php");
		WordSearchSolver::solveAndPrintSolution("word-search.txt");
	?>

</body>

</html>