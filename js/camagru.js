window.onscroll = function() {myFunction()};

var header = document.getElementById("header");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

function open_webcam(){
	let imup = document.getElementById("image_up");
	imup.style.display = "none";
	document.getElementById("or").style.display = "none";
	document.getElementById("webcam_btn").style.display = "none";
	document.getElementById("back_btn").style.display = "block";
	document.getElementById("webcam").style.display = "block";

	imup.required = false;
}

function back_webcam(){
	let imup = document.getElementById("image_up");
	imup.style.display = "block";
	document.getElementById("or").style.display = "block";
	document.getElementById("webcam_btn").style.display = "block";
	document.getElementById("back_btn").style.display = "none";
	document.getElementById("webcam").style.display = "none";

	imup.required = true;
}