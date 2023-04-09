var i = 0;
var j = 0;

var txtList = [
	"Influencers", "Youtubers", "Marketing", "Artists", "Teachers", "Sharing", "Emails", "Tracking",
]

var blinkingCursor = document.getElementById("cursor");
var speed = 100;
var typeDelay = 2000;
var eraseDelay = 3000;

var txt = txtList[j];

if (blinkingCursor) {
	function autoType() {
		blinkingCursor.removeAttribute("class")

		if (i < txt.length) {
			document.getElementById("demo").innerHTML += txt.charAt(i);
			i++;
			setTimeout(autoType, speed);
		} else {
			blinkingCursor.setAttribute("class", "typing")
			setTimeout(autoErase, eraseDelay)
		}
	}

	function autoErase() {
		blinkingCursor.removeAttribute("class")

		if (i > 0) {
			var demoText = document.getElementById("demo")
			var text = demoText.textContent
			demoText.textContent = text.substring(0, i - 1);
			i--;
			setTimeout(autoErase, speed);
		} else {
			blinkingCursor.setAttribute("class", "typing")
			j++;
			if (j > (txtList.length - 1)) { j = 0; }
			txt = txtList[j]
			setTimeout(autoType, typeDelay)
		}
	};

	document.addEventListener("DOMContentLoaded", function () {
		if (txtList.length > 0) {
			blinkingCursor.setAttribute("class", "typing")
			setTimeout(autoType, 3000)
		}
	});
}