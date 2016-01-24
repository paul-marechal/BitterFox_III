var W = window;
var PROGRAMS;

$.get('conf/programs.json', {}, function(data) {
	PROGRAMS = data;
});

// Repositionnement sur le "bureau"
function constrain(x, min, max) {
	return (x < min) ? min : ((max < x) ? max : x);
} function constrainInto(thing, area) {
	if (!(area instanceof $)) area = $(area);
	var offset = thing.offset();
	
	var x = constrain(
		offset.left,
		0, area.width() - thing.outerWidth()
	);
	
	var y = constrain(
		offset.top,
		0, area.height() - thing.outerHeight()
	);
	
	// On reposition le truc
	thing.offset({left: x, top: y});
}

// Pour télécharger un fichier
function Download(file) {
	url = encodeURIComponent(file);
	document.getElementById('IFRAME').src = '?n=dora&path=' + url;
	return {pop: function() {}};
}