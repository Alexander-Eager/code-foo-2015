Question 4
==========

**Using [this API] (http://ign-apis.herokuapp.com), pull and display a list of
both articles and videos.**

This is simple enough. Since I wrote question 3 in PHP, and question 5 is going
to essentially require JavaScript, I decided to use JavaScript for this
question.

All of the JavaScript code that actually fetches the articles/videos is in
`list_item.js`.

There are a few notable bugs in this solution that you should avoid: the input
is not validated (so do not enter anything other than positive integers in
the text boxes), and every once in a while an "undefined" shows up as a
subtitle in the list. I decided to focus my efforts on the ListItem objects
and API work instead of the format of the output and user-friendliness of the
input, since this question is really just a stepping stone to question 5
anyway.