var Focused = null;
var topZindex = 22;

function setFocus(thing) {
	if (Focused) Focused.removeFocus(); // On enlève le focus
	if (thing) thing.setFocus(); // On ajoute le focus
	Focused = thing; // On stocke l'objet focused
}

function Window(name) {
	var window = T.window.clone();
	var trail = T.trail.clone();
	var path = window.find('.window-path-input');
	var header = window.find('.window-header');
	var content = window.find('.window-content');
	var title = window.find('.window-title');
	var context = addContextMenu(window);
		addContextMenu(trail);
	trail.context = context;
	
	// Attributs
	window.trail = trail;
	window.path = path;
	window.header = header;
	window.content = content;
	
	// Réglage de l'application
	window.app = function(app) {
		window.attr('app', app);
	};
	
	// Temps des animations
	window.animDuration = 100;
	
	// On passe le focus
	window.setFocus = function() {
		window.css('z-index', ++topZindex);
		window.removeClass('minimized');
		trail.removeClass('minimized');
		window.addClass('focused');
		trail.addClass('focused');
	};
	
	// On perd le focus
	window.removeFocus = function() {
		window.removeClass('focused');
		trail.removeClass('focused');
	};
	
	// On règle le titre
	window.setTitle = function(title = 'Unnamed Window') {
		window.find('.window-title').text(title);
		trail.find('.window-trail-text').text(title);
		trail.attr('title', title);
		context.setTitle(title);
		window.title = title;
	}; window.setTitle(name);
	window.getTitle = function() {return window.title;};
	
	// Get/Set path
	window.getPath = function() {return path.val();};
	window.setPath = function(p) {return path.val(p);};
	
	window.getLocation = function() {return window.location;};
	window.setLocation = function(p) {
		window.setPath(p);
		return window.location = p;
	};
	
	// Mise à jour de la fenêtre
	window.loading = function() {window.addClass('loading');};
	window.onupdate = function() {};
	window.update = function() {
		window.onupdate();
		window.removeClass('loading');
	};
	
	// Rechargelent
	window.onreload = function() {};
	window.noreload = function() {delete window.abortReload;};
	window.reload = function() {
		
		// Système d'arrêt de récurrence
		if (window.abortReload) {
			window.noreload();
			window.close();
			return;
		} else {
			window.abortReload = true;
			window.onreload();
		}
	};
	
	// Appui sur "enter"
	path.keyup(function(e) {
		if (e.which == KEYS.enter) window.update();
	});
	
	// Bouton de rafraîchissement
	window.find('.refresh').click(function() {
		window.update();
	});
	
	// On ajoute une ligne d'entête
	window.addHeader = function(type = 'row') {
		var row = $("<div class='row window-header-"+type+"'/>");
		
		// Ajout d'un bouton
		row.addButton = function(
			label = 'Null Button',
			func=function() {alert('Useless');},
			cls = undefined
			) {
			
			var button = $("<div class='window-button'/>");
			if (cls) button.addClass(cls);
			button.text(label);
			button.click(func);
			
			row.append(button);
			return button;
		};
		
		header.append(row);
		return row;
	}
	
	// Mise au premier plan
	window.mousedown(function() {
		setFocus(window);
	});
	
	// Déplacement par grab de l'entête
	title.mousedown(function() {
		$('html').addClass('moveCurs');
		moving = window;
	});
	
	// Redimensionnement au double click
	title.dblclick(function() {
		window.resize();
	});
	
	// Redimmensionnement
	window.find('.window-resizer').mousedown(function() {
		$('html').addClass('resizeCurs');
		resizing = window;
	});
	
	// Mise au premier plan
	trail.click(function() {
		if (Focused == window) window.minimize();
		else setFocus(window);
	});
	
	// Fermeture
	window.onclose = function() {};
	window.close = function() {
		
		// Fermeture du trail
		trail.slideUp(window.animDuration, function() {
			trail.remove();
		});
		
		// Fermeture de la fenêtre
		window.hide(window.animDuration, function() {
			window.onclose();
			window.remove();
		});
	};
	
	// Réduction
	window.minimize = function() {
		window.addClass('minimized');
		trail.addClass('minimized');
		setFocus(null);
	};
	
	// Redimensionnement
	window.resize = function() {
		window.toggleClass('maximized'); 
	};
	
	// Bouton de fermeture
	window.find('.close').click(function(e) {
		window.close()
		e.stopPropagation();
	});
	
	// Bouton de redimensionnement
	window.find('.resize').click(function() {
		window.resize();
	});
	
	// Bouton de réduction
	window.find('.minimize').click(function(e) {
		window.minimize();
		e.stopPropagation();
	});
	
	// Fonction d'affichage de la fenêtre
	window.pop = function() {
		window.hide();
		trail.hide();
		
		$('#desktop').prepend(window);
		$('#trail-list').append(trail);
		window.update();
		
		setFocus(window);
		window.show(window.animDuration);
		trail.slideDown(window.animDuration);
		
		return window;
	}
	
	// Options contextuelles
	context.addOption('Close', window.close);
	
	return window;
}