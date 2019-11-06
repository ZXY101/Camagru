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

function deletePost() {
	let r = confirm("Are you sure?");
	if (r == true) {
		user_id = document.getElementById('user_id').value;
		post_id = document.getElementById('post_id').value;
		let params = "user_id="+user_id+"&post_id="+post_id;

		let xhr = new XMLHttpRequest();
		xhr.open('POST', '/Camagru/inc/delete_post.php', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function(){
			if (this.status == 200){
				console.log(this.responseText);
				window.location = '/Camagru/'
			}
		};

		xhr.send(params);
	}
}

let order;

function orderBy(){
	let option = document.getElementById('orderBy').value;
	order = option;

	let params = "order_by="+option;

	let xhr = new XMLHttpRequest();
	xhr.open('POST', '/Camagru/index.php?page=feed.inc.php', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function(){
		if (this.status == 200){
			document.documentElement.innerHTML = this.responseText;
		}
	};

	xhr.send(params);
}

function previewImg(event) {
	{
		var reader = new FileReader();
		reader.onload = function()
		{
		 var output = document.getElementById('preview');
		 document.getElementById('preview_div').style.display = 'block';
		 output.src = reader.result;
		}
		reader.readAsDataURL(event.target.files[0]);
	}
}