function getDocumentHeight() {
	const body = document.body;
	const html = document.documentElement;
	
	return Math.max(
		body.scrollHeight, body.offsetHeight,
		html.clientHeight, html.scrollHeight, html.offsetHeight
	);
};

function getScrollTop() {
	return (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
}

function loadMore(){
	displayedPosts = document.getElementById('post_count').value;

	let params = "displayed_posts="+displayedPosts+"&order_by="+order;

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

window.addEventListener('scroll', scrolling);

function scrolling(){
	if (getScrollTop() < getDocumentHeight() - window.innerHeight) return;
		loadMore();
}
