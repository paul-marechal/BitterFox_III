function FireCatcher(text='Fuck Off') {
	var overlay = T.firecatcher.clone();
	var modal = overlay.find('.firecatcher-modal');
	var label = modal.find('.firecatcher-label');
	var controls = modal.find('.firecatcher-controls');
	
	// Joli texte
	label.text(text);
	
	overlay.addInput = function() {
		var input = $("<input class='firecatcher-input'/>");
		controls.append(input);
		return input;
	};
	
	// On ajoute une ligne de boutons
	overlay.addButtons = function() {
		var row = $("<div class='firecatcher-buttons'></div>");
		controls.append(row);
		
		// On ajoute des boutons
		row.addButton = function(jesus, func) {
			var button = $("<div class='window-button'/>");
			button.text(jesus);
			row.append(button);
			
			// Moche
			button.click(function() {func(overlay);});
			
			return button;
		}
		
		return row;
	};
	
	// Pop
	overlay.pop = function() {
		overlay.hide();
		$('html').append(overlay);
		overlay.fadeIn(100);
		return overlay;
	};
	
	overlay.out = function() {
		overlay.fadeOut(100, function() {
			overlay.remove();
			overlay = null;
		});
		return overlay;
	};
	
	// On annule
	modal.dblclick(function(e) {e.stopPropagation();});
	overlay.dblclick(function() {overlay.out();});
	
	return overlay;
}