window.onload = function() {
	var dbRadio = document.getElementById("dbradio");
	var fileRadio = document.getElementById("fileradio");
	
	if(dbRadio != null) {
		if(dbRadio.checked) {
			dbRadioClick();
		}
		
		dbRadio.onclick = dbRadioClick;
		fileRadio.onclick = fileRadioClick;
	}
}

function dbRadioClick() {
	document.getElementById("filearea").style.display = "none";
}

function fileRadioClick() {
	document.getElementById("filearea").style.display = "flex";
}