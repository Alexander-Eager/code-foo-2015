
// creates a ListItem object with the given values
function ListItem(numInList, isArticle, title, subtitle,
	time, url) {
	this.numberInList = numInList;
	this.isArticle = isArticle;
	this.title = title;
	this.subtitle = subtitle;
	this.time = time;
	this.url = url;
	this.getHtmlElement = function() {
		// for question 4, this is just a bunch of text
		// in question 5, I will edit this to make the proper
		// formatting for the list
		return "" + this.numberInList + ": " + this.title +
			"<br/>" + this.subtitle + "<br/>" + this.time +
			"<br/>" + this.url + "<br/>";
	}
}

// creates a ListItem object from an article's metadata
function createListItemFromArticle(objFromApi, numInList) {
	// the more complicated stuff are the date and the url

	// objFromApi.publishDate is a formatted string, so parse it
	var publishDate = objFromApi.publishDate;
	publishDate = publishDate.substring(0, 10) + " " +
		publishDate.substring(11, 19);
	var date = new Date(publishDate.replace(/-/g, "/"));
	// format of the date is month/day/year
	// may change later
	var time = "" + (date.getMonth() + 1) + "/" + date.getDate() + "/" +
		date.getFullYear();

	// the URL is based on objFromApi.slug, and the above date
	var month = (date.getMonth >= 9) ? ("" + (date.getMonth() + 1))
		: ("0" + (date.getMonth() + 1));
	var url = "http://www.ign.com/articles/" + date.getFullYear() +
		"/" + month + "/" + date.getDate() + "/" +
		objFromApi.slug;

	// now just add in other parameters
	return new ListItem(numInList, true, objFromApi.headline,
		objFromApi.subHeadline, time, url);
}

// creates a ListItem object from a video's metadata
function createListItemFromVideo(objFromApi, numInList) {
	// only complicated thing is time/duration
	// objFromApi.duration is in seconds, so have to convert
	var duration = objFromApi.duration;
	var hours = Math.floor(duration / 3600);
	duration -= hours * 3600;
	var minutes = Math.floor(duration / 60);
	duration -= minutes * 60;
	var seconds = duration;

	// hours only if needed, and allow 1 or 2 digits
	// minutes only if duration >= 60, allow 1 digit if duration < 3600
	// seconds always, allow 1 digit if duration < 60 (and include 's')
	var hoursString = "";
	var minutesString = "";
	var secondsString = "";
	if (hours > 0) {
		hoursString = "" + hours + ":";
	}
	if (minutes >= 10) {
		minutesString = "" + minutes + ":";
	} else if (minutes > 0 && hours == 0){
		minutesString = "" + minutes + ":";
	} else if (hours > 0) {
		minutesString = "0" + minutes + ":";
	}
	if (objFromApi.duration < 60) {
		secondsString = "" + seconds + "s";
	} else if (seconds >= 10) {
		secondsString = "" + seconds;
	} else {
		secondsString = "0" + seconds;
	}
	var time = hoursString + minutesString + secondsString;

	// now just add in other parameters
	// TODO figure out subtitle
	return new ListItem(numInList, false, objFromApi.title,
		objFromApi.description, time, objFromApi.url);
}

// retrieves a list of articles or videos, depending on the
// wantArticles flag. The callback function is called once the
// resulting list is retrieved
function retrieveList(wantArticles, startIndex, numResults,
	callback) {
	// get the JSON from the API
	var action = wantArticles ? "articles" : "videos";
	var urlToRequest = "http://ign-apis.herokuapp.com/" + action +
		"?startIndex=" + startIndex + "&count=" + numResults;

	$.ajax(urlToRequest, {
		dataType: "jsonp",

		success: function(apiResults, status, xhr) {
			apiResults = apiResults.data;
			// iterate through the apiResults and get the desired list
			// items
			var ans = [];
			for (var i = 0; i < apiResults.length; i ++) {
				if (wantArticles) {
					ans[ans.length] = createListItemFromArticle(
						apiResults[i].metadata, i + 1);
				} else {
					ans[ans.length] = createListItemFromVideo(
						apiResults[i].metadata, i + 1);
				}
			}
			// now pass ans to the callback
			callback(ans);
		},

		error: function(a, b, c) {
			alert("ERROR " + b);
		}
	});
}

// interprets the formOnLeft for question 4
function generateListFromForm() {
	// get the values from the form (not actually a form tag though)
	var action = $("input[name='action']:checked").val();
	var startIndex = $("input[name='startIndex']").val();
	var numResults = $("input[name='numResults']").val();

	var wantArticles = (action === "articles");
	startIndex = parseInt(startIndex);
	numResults = parseInt(numResults);

	// get the list of articles using those values and get their text
	retrieveList(wantArticles, startIndex, numResults,
		function(list) {
			var newInnerHTML = "";
			for (var i = 0; i < list.length; i ++) {
				newInnerHTML += list[i].getHtmlElement() + "<br/><br/>";
			}

			// put that text in the listOnRight
			document.getElementById("listOnRight").innerHTML =
				newInnerHTML;
		});
}