function hide(el) {
	if(el.style.opacity == "") {
		el.style.opacity = 1;
	}
	
	if(el.style.opacity > 0) {
		el.style.opacity -= 0.1;
		setTimeout(function() {
			hide(el);
		}, 100);
	}
}

window.onload = setTimeout(function() {
	var notificationElement = document.getElementById("notification");
	
	if(notificationElement != null) {
		hide(notificationElement);
	}
}, 10000);