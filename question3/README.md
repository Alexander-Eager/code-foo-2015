Question 3
==========

**Write a program to find the given words from the included word search.**

I have moved that word search text file to this directory (`question3/`).

Please forgive the lack of boundary checking on the file input for this question. If I have time I will go back and fix that later, but for now it will have to remain as-is. For the purposes if this question, I've limited the size of the input to be at most 26 columns wide and 99 columns tall.

The basic idea of my algorithm is this:

*Step 1*: Represent the words that you need to look for as a list of trees. Words are represented by different branches in the forest.

So the words:

- POKEMON
- POKEBALL
- POKE
- ZELDA
- LINK
- LUCAS

Would be a in a forest that looks something like:

```
	P           Z            L
	|           |           / \
	O           E          I   U
	|           |          |   |
	K           L          N   C
	|           |          |   |
	E*          D          K*  A
   / \          |              |
  M   B         A*             S*
  |   |
  O   A
  |   |
  N*  L
      |
      L*
```

Where an asterisk next to a letter indicates that the letter is the end of a word.

*Step 2*: Use this forest to search through the grid of characters in the natural, brute force method you would use when looking through it by hand.