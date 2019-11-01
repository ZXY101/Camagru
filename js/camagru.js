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
const clearBtn = document.getElementById("clear_btn");
const submitBtn = document.getElementById('submit_btn');
const inputBtn = document.getElementById('submit_input');
const wc_img = document.getElementById('wc_img');

let wc_stream;
let width = 500;
let height = 0;
let streaming = false;
let using_wc = false;

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

clearBtn.addEventListener('click', function(e){
	clearPicture();
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

		if (wc_img.childNodes[0])
			wc_img.removeChild(wc_img.childNodes[0]);
		wc_img.appendChild(img);
		photoBtn.style.display = 'none';
		clearBtn.style.display = 'block';
	}
}

function clearPicture (){
	photoBtn.style.display = 'block';
	clearBtn.style.display = 'none';
	video.style.display = 'inline-block';
	if (wc_img.childNodes[0])
			wc_img.removeChild(wc_img.childNodes[0]);
}


function open_webcam(){
	imup.style.display = "none";
	document.getElementById("or").style.display = "none";
	document.getElementById("webcam_btn").style.display = "none";
	document.getElementById("back_btn").style.display = "block";
	document.getElementById("webcam").style.display = "block";

	navigator.mediaDevices.getUserMedia({video: true, audio: false})
	.then(function(stream){
		video.srcObject = stream;
		wc_stream = stream;
		video.play()
	})
	.catch(function(error){
		console.log('Error: '+error);
	})

	submitBtn.style.display = 'block';
	inputBtn.style.display = 'none';

	imup.required = false;
	using_wc = true;
}

function back_webcam(){
	imup.style.display = "block";
	document.getElementById("or").style.display = "block";
	document.getElementById("webcam_btn").style.display = "block";
	document.getElementById("back_btn").style.display = "none";
	document.getElementById("webcam").style.display = "none";

	wc_stream.getTracks().forEach(function(track) {
		track.stop();
	  });

	submitBtn.style.display = 'none';
	inputBtn.style.display = 'block';

	imup.required = true;
	using_wc = false;
}
