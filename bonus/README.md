Bonus Question
==============

**Programmatically create a game similar to Battleship™ with one AI-controlled opponent and a human-controlled player.**

I do not have time to fully implement the bonus question, but I can at least describe how I would implement the computer controlled player. Assuming that there are ships of length 2, 3, 4 and 5, do the following:

- The game should be set up as a repeating loop, where each player takes a turn during each iteration of the loop.
- The AI will determine its actions by an action identifier and a space on which to
commit the action, which are added to a queue of actions to take in subsequent terms.
- The AI starts off searching for length 5 ships, and then decreases one by one all the way to 2 as it eliminates all ships of that length.
- In the "search" mode, the AI randomly hits any space where `row = col % ship_length`.
- Once it makes a hit (which this tiling guarantees in a near-minimal amount of turns), it enters "destroy" mode.
- In the "destroy" mode, it continuously hits to the left until it no longer hits a ship, then the right, then up, then down, stopping if the ships it has destroyed cover all of the hits that it got in this round of "destroy". If it destroys a ship, but there are still hits that it got in this "destroy" mode that remain unexplained by the size of the destroyed ship, this executes a "destroy" around those hits.

It is somewhat hard to explain this AI without code, so I am sorry for the confusing language. It basically implements the usual method of play that you would take when playing Battleship: randomly tile the ocean until you get a hit ("search" mode), and then search around the hit until you've sunk all ships in the area ("destroy" mode). The key insight here is that the spaces it randomly tiles are those which satisfy `row = col % ship_length`, since doing so leaves no more than `ship_length - 1` consecutive unhit spaces vertically or horizontally, ensuring that any ship with `ship_length` has been hit on part of it.