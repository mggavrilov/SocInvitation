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
	if(!document.getElementById("notification").classList.contains("jsnotif")) {
		var notificationElement = document.getElementById("notification");

		hide(notificationElement);
	}
}, 10000);

window.onload = function() {	
	if(document.getElementById("table") != null) {
		var dataTable = new DataTable("#table");
	}
}