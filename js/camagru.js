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

const imup = document.getElementById("image_up");
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const photoBtn = document.getElementById("photo_btn");
const wc_img = document.getElementById('wc_img');

let width = 500;
let height = 0;
let streaming = false;

navigator.mediaDevices.getUserMedia({video: true, audio: false})
	.then(function(stream){
		video.srcObject = stream;
		video.play()
	})
	.catch(function(error){
		console.log('Error: '+error);
})

video.addEventListener('canplay', function(e){
	if (!streaming){
	height = video.videoHeight / (video.videoWidth / width);

	video.setAttribute('width', width);
	video.setAttribute('height', height);
	canvas.setAttribute('width', width);
	canvas.setAttribute('height', height);

	streaming = true;
	}
}, false);

photoBtn.addEventListener('click', function(e){
	takePicture();

	e.preventDefault();
}, false);

function takePicture(){
	const context = canvas.getContext('2d');
	if (width && height){
		canvas.width = width;
		canvas.height = height;

		video.style.display = 'none';

		context.drawImage(video, 0, 0, width, height);

		const imgUrl = canvas.toDataURL('image/png');

		const img = document.createElement('img');

		img.classList.add('wc');
		img.classList.add('w3-border');
		img.classList.add('w3-border-red');
		img.classList.add('w3-image');

		img.setAttribute('src', imgUrl);

		wc_img.appendChild(img);

	}
}


function open_webcam(){
	imup.style.display = "none";
	document.getElementById("or").style.display = "none";
	document.getElementById("webcam_btn").style.display = "none";
	document.getElementById("back_btn").style.display = "block";
	document.getElementById("webcam").style.display = "block";

	imup.required = false;
}

function back_webcam(){
	imup.style.display = "block";
	document.getElementById("or").style.display = "block";
	document.getElementById("webcam_btn").style.display = "block";
	document.getElementById("back_btn").style.display = "none";
	document.getElementById("webcam").style.display = "none";

	imup.required = true;
}
