/*$(document).on("oncontextmenu", function(e) {
	e.preventDefault();
	//e.target
});*/

// Menu contextuel
var CONTEXTMENU = T.contextmenu.clone();

// Make it appear
CONTEXTMENU.pop = function(x = 0, y = 0) {
	desktop = $('#desktop');
	desktop.prepend(CONTEXTMENU);
	CONTEXTMENU.offset({top: y, left: x});
	constrainInto(CONTEXTMENU, desktop);
};

// Make it disappear
CONTEXTMENU.out = function() {
	CONTEXTMENU.remove();
};

// Ajoute un contexte à l'élément
function addContextMenu(thing) {
	var context = {length: 0, options: {}};
	thing.addClass('context');
	thing.context = context;
	
	// On règle le nom du menu contextuel
	context.title = 'Context Menu'
	context.setTitle = function(title) {
		return context.title = title;
	};
	
	// Déclenchement du menu contextuel perso sur l'élément
	thing.on('contextmenu', function(e) {
		e.stopPropagation();
		e.preventDefault();
		thing.popContextMenu(e.pageX, e.pageY);
	});
	
	// Ajout d'une ligne
	context.addRow = function(row) {
		return context.options[++context.length] = row;
	};
	
	// Ajout d'une option
	context.addOption = function(label, func=function() {}) {
		return context.addRow({label: label, func: func, enabled: true});
	};
	
	// AJout d'un séparateur
	context.addSeparator = function() {
		return context.addRow('<hr>');
	};
	
	// Apparition du menu contextuel
	thing.popContextMenu = function(x = 0, y = 0) {
		var context = thing.context;
		CONTEXTMENU.html(null);
		
		title = $("<div id='context-title'/>");
		title.text(context.title);
		CONTEXTMENU.append(title);
		
		$.each(context.options, function(i, opt) {
			option = $("<div class='context-item'/>");
			if (typeof opt == 'string') {
				option = opt;
			} else if (opt.enabled) {
				option.text(opt.label);
				option.click(function(e) {
					e.stopPropagation();
					CONTEXTMENU.out();
					opt.func();
				});
			} else return;
			
			CONTEXTMENU.append(option);
		});
		
		CONTEXTMENU.pop(x, y);
	};
	
	return context
}

$(document).click(function() {
	CONTEXTMENU.out();
});