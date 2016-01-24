var KEYPRESSED = {};
var MOUSEPRESSED = {};
var HOVERING = {};

var KEYS = {
	"enter": 13,
	"backspace": 8,
	"space": 32,
	"shift": 16,
	"ctrl": 17,
	"tab": 9,
	"end": 37,
	"home": 36
};

var MOUSES = {
	"left": 1,
	"middle": 2,
	"right": 3
};

function keyPressed(key) {
	return KEYPRESSED[key] == true;
}

function mousePressed(mouse) {
	return MOUSEPRESSED[mouse] == true;
}

function hover(thing) {
	thing.mouseenter(function() {
		HOVERING[thing] = true;
	});
	
	thing.mouseleave(function() {
		delete HOVERING[thing];
	});
}

function isOver(thing) {
	return HOVERING[thing] == true;
}

$(document).keydown(function(e) {
	KEYPRESSED[e.which] = true;
});

$(document).keyup(function(e) {
	delete KEYPRESSED[e.which];
});

$(document).mousedown(function(e) {
	MOUSEPRESSED[e.which] = true;
});

$(document).mouseup(function(e) {
	delete MOUSEPRESSED[e.which];
});