// creates a ListItem object with the given values
function ListItem(numInList, isArticle, title, subtitle,
	time, url) {
	this.numberInList = numInList;
	this.isArticle = isArticle;

	if (title !== undefined) {
		this.title = title;
	} else {
		this.title = "No Title";
	}

	if (subtitle !== undefined) {
		this.subtitle = subtitle;
	} else {
		this.subtitle = "";
	}

	if (time !== undefined) {
		this.time = time;
	} else {
		this.time = "";
	}

	this.url = url;

	this.getHtmlElement = function() {
		// this is just the HTML for the ListItem when you want to display it
		var ans = "<tr onclick='location.href = \"" + this.url + "\"'>";
		if (this.numberInList < 10) {
			ans += "<td class='left'>0" + this.numberInList + "</td>";
		} else {
			ans += "<td class='left'>" + this.numberInList + "</td>";
		}
		ans += "<td class='middle'>";
		ans += "<div class='title'>" + this.title + "</div>";
		ans += "<div class='subtitle'>" + this.subtitle + "</div>";
		ans += "</td>";
		ans += "<td class='right'>" + this.time + "</td>";
		ans += "</tr>";
		return ans;
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
	} else if (hours == 0){
		minutesString = "" + minutes + ":";
	} else if (hours > 0) {
		minutesString = "0" + minutes + ":";
	}
	if (seconds >= 10) {
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
						apiResults[i].metadata, i + startIndex);
				} else {
					ans[ans.length] = createListItemFromVideo(
						apiResults[i].metadata, i + startIndex);
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

// loads 10 more results of whatever wantArticles is
function loadMore(wantArticles) {
	// get the starting index
	var startIndex = document.getElementById("load_more").numResults + 1;
	// retrieve and display the list of articles
	retrieveList(wantArticles, startIndex, 10, function(list) {
		var newInnerHTML = "";
		for (var i = 0; i < list.length; i ++) {
			newInnerHTML += list[i].getHtmlElement();
		}
		document.getElementById("list").innerHTML += newInnerHTML;
	});
	document.getElementById("load_more").numResults += 10;
}

// loads a list of 10 videos initially
function loadList(wantArticles) {
	// change the title
	var title = wantArticles ? "ARTICLES" : "VIDEOS";
	document.getElementById("title").innerHTML = title;

	// change the videos/articles buttons
	var videosButton = document.getElementById("videosButton");
	var articlesButton = document.getElementById("articlesButton");
	if (wantArticles) {
		articlesButton.style["background-color"] = "#BD1313";
		articlesButton.style["color"] = "#FFFFFF";
		videosButton.style["background-color"] = "#FFFFFF";
		videosButton.style["color"] = "#BD1313";
	} else {
		articlesButton.style["background-color"] = "#FFFFFF";
		articlesButton.style["color"] = "#BD1313";
		videosButton.style["background-color"] = "#BD1313";
		videosButton.style["color"] = "#FFFFFF";
	}

	// change the load_more button's text and onclick
	var loadMoreButton = document.getElementById("load_more");
	var loadMoreText = wantArticles ? "articles" : "videos";
	loadMoreButton.innerHTML = "Load more " + loadMoreText;
	loadMoreButton.numResults = 0;
	loadMoreButton.onclick = function() {
		loadMore(wantArticles);
	};

	// remove all current list items and load more
	document.getElementById("list").innerHTML = "";
	loadMore(wantArticles);
}