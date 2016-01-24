var PASTA = {};

function pastaReset() {
	PASTA = {};
}

function pastaTo(path) {
	$.post('?m=pasta', {files: PASTA}, function(data) {
		
	});
}