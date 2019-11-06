const imup = document.getElementById("image_up");
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const photoBtn = document.getElementById("photo_btn");
const clearBtn = document.getElementById("clear_btn");
const inputBtn = document.getElementById('submit_input');
const wc_img = document.getElementById('wc_img');
const imgForm = document.getElementById('img_form');

let wc_stream;
let width = 500;
let height = 0;
let streaming = false;
let pic_taken = false;

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


let imgg;
const context = canvas.getContext('2d');
const stickers = document.getElementById('stickers');

function takePicture(){
	pic_taken = true;
	if (width && height){
		canvas.width = width;
		canvas.height = height;
		stickers.style.display = 'none';

		context.drawImage(video, 0, 0, width, height);
		if (sticker1)
			context.drawImage(sticker1, 0, 0, 100, 100);
		if (sticker2)
			context.drawImage(sticker2, 200, 0, 100, 100);
		if (sticker3)
			context.drawImage(sticker3, 400, 0, 100, 100);
		
		const img = document.createElement('img');
		const imgUrl = canvas.toDataURL('image/png');


		img.classList.add('wc');
		img.classList.add('w3-border');
		img.classList.add('w3-border-red');
		img.classList.add('w3-image');

		img.setAttribute('src', imgUrl);

		imgg = imgUrl;

		if (wc_img.childNodes[0])
			wc_img.removeChild(wc_img.childNodes[0]);
		wc_img.appendChild(img);
		photoBtn.style.display = 'none';
		clearBtn.style.display = 'block';
		canvas.style.display = 'none';
		video.style.display = 'none';
	}
}

function submitForm(e){
	e.preventDefault();

	if (pic_taken){
		let params = "title="+document.getElementById('img_title').value+"&message="+document.getElementById('message').value+"&image="+imgg;

		let xhr = new XMLHttpRequest();
		xhr.open('POST', '/Camagru/inc/addFWC.php', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function(){
			if (this.status == 200){
				console.log(this.responseText);
				window.location.href = '/Camagru/'
			}
		};

		xhr.send(params);
	}
}

function clearPicture (){
	pic_taken=false;
	photoBtn.style.display = 'block';
	clearBtn.style.display = 'none';
	video.style.display = 'inline-block';
	canvas.style.display = 'inline-block';
	stickers.style.display = 'block';
	sticker1 = null;
	sticker2 = null;
	sticker3 = null;

	context.clearRect(0, 0, canvas.width, canvas.height);
	if (wc_img.childNodes[0])
			wc_img.removeChild(wc_img.childNodes[0]);
}


function open_webcam(){
	imup.style.display = "none";
	document.getElementById("or").style.display = "none";
	document.getElementById("webcam_btn").style.display = "none";
	document.getElementById("preview_div").style.display = "none";
	document.getElementById("back_btn").style.display = "block";
	document.getElementById("webcam").style.display = "block";
	canvas.style.display = 'inline-block';
	video.style.display = 'inline-block';
	stickers.style.display = 'block';

	navigator.mediaDevices.getUserMedia({video: true, audio: false})
	.then(function(stream){
		video.srcObject = stream;
		wc_stream = stream;
		video.play()
	})
	.catch(function(error){
		console.log('Error: '+error);
	})

	imgForm.addEventListener('submit', submitForm);
	imup.required = false;
}

function back_webcam(){
	imup.style.display = "block";
	document.getElementById("or").style.display = "block";
	document.getElementById("webcam_btn").style.display = "block";
	document.getElementById("preview_div").style.display = "block";
	document.getElementById("back_btn").style.display = "none";
	document.getElementById("webcam").style.display = "none";

	wc_stream.getTracks().forEach(function(track) {
		track.stop();
	  });

	imgForm.removeEventListener('submit', submitForm);
	imup.required = true;
}


let sticker1;
let sticker2;
let sticker3;

function addSticker1(element){
	sticker1 = element;
	context.drawImage(sticker1, 0, 0, 100, 100);
}

function addSticker2(element){
	sticker2 = element;
	context.drawImage(sticker2, 200, 0, 100, 100);
}

function addSticker3(element){
	sticker3 = element;
	context.drawImage(sticker3, 400, 0, 100, 100);
}

