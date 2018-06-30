var showinvgen;

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
	
	var scanfb = document.getElementById("scanfb");
	var invgendiv = document.getElementById("invgendiv");
	
	invgendiv.style.display = "none";
	showinvgen = false;
	
	scanfb.onclick = scanfbClick;
	
	document.getElementById("processform").addEventListener("submit", function(e) {
		if(document.getElementById("dbradio").checked) {
			if(document.getElementById("directory").value == "" && !document.getElementById("scanfb").checked) {
				document.getElementById("notification").className += " jsnotif ";
				document.getElementById("notification").style.opacity = 1;
				document.getElementById("notification").innerHTML = "Формата не може да бъде изпратена в сегашния си вид. Моля, попълнете поне още едно поле.";
				document.getElementById("notification").className += " error ";
				e.preventDefault();
			}
		}
		else if(document.getElementById("fileradio").checked) {
			if(document.getElementById("file").value == "") {
				document.getElementById("notification").className += " jsnotif ";
				document.getElementById("notification").style.opacity = 1;
				document.getElementById("notification").innerHTML = "Моля, прикачете файл.";
				document.getElementById("notification").className += " error ";
				e.preventDefault();
			}
		}
	});
}

function scanfbClick() {
	if(showinvgen) {
		invgendiv.style.display = "none";
		showinvgen = false;
	}
	else {
		invgendiv.style.display = "block";
		showinvgen = true;
	}
}

function dbRadioClick() {
	document.getElementById("filearea").style.display = "none";
}

function fileRadioClick() {
	document.getElementById("filearea").style.display = "flex";
}