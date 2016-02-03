function DaVinci(path='.') {
	var window = Window();
	var image = window.content;
	
	// On règle l'appli
	window.app('DaVinci');
	
	// Where art you ?
	path = window.setLocation(path);
	
	// On recharge bien la page...
	window.onreload = function() {
		window.showImage(window.getLocation());
	};
	
	window.onupdate = function() {
		window.showImage(window.getPath());
	};
	
	// Lecture de l'image
	window.showImage = function(path) {
		$.post('?m=davinci', {action: 'getImage', path: path}, function(data) {
			if (data.error) return window.reload();
			window.noreload();
			
			window.setLocation(data.info.path);
			window.setTitle(data.info.name);
			
			url = encodeURIComponent(data.info.path);
			image.css('background-image', 'url(reader?thumbnail=' + url + ')');
		});
	};
	
	return window;
}