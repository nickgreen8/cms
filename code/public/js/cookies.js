function cookieExists(name) {
	//Check for cookie existance
	if (document.cookie.indexOf(name + '=') === -1) {
		console.log('No cookie found');
		return false;
	} else {
		console.log('Cookie found');
		return true;
	}
}

function getCookie(name) {
	//Get the cookies
	var cookies = document.cookie;
	//Set the cookie key
	var key     = name + '=';

	//Check for the cookies existance
	if (cookies.indexOf(key) === -1) {
		console.log('No cookie found');
		return null;
	}

	//Get the cookie details
	var i       = cookies.indexOf(key);
	var cookie  = cookies.substr(i + key.length);

	//Return the cookie value
	if (cookie.indexOf(';') === -1) {
		return cookie;
	} else {
		return cookie.substr(0, cookie.indexOf(';'));
	}
}

function setCookie(name, value, expiry, path) {
	//Get the current date
	var d = new Date();
	//Calculate the cookie expiry
    d.setTime(d.getTime() + ((typeof expiry === "undefined" ? 7 : expiry) * 24 * 60 * 60 * 1000));

    //Build cookie string
    var expires = "expires=" + d.toUTCString();
    var cookie = name + "=" + value + "; " + expires + ";";

    //Check for specified path
    if (typeof expiry !== "undefined") {
    	cookie += " path=" + path + ";"
    }

    //Add cookie
    document.cookie = cookie;
    return cookie;
}

function deleteCookie(name) {
	//Set the cookie as empty
	return setCookie(name, '', 0, '/');
}

function cookieNotice() {
	//Check if the cookie notice has been seen
	if (!cookieExists('ng_cookies')) {
		//Display the notice
		document.getElementById('cookie_notice').className = '';
	}
}

function acknowledgeCookieMessage() {
	//Hide the message
	animate('slideUp', document.getElementById('cookie_notice'));
	//Add cookie
	setCookie('ng_cookies', true, 365, '/');
}

function init() {
	cookieNotice();
	document.getElementById('cookie_notice').getElementsByClassName('close')[0].addEventListener('click', acknowledgeCookieMessage, false);
}

document.addEventListener('DOMContentLoaded', init, false);