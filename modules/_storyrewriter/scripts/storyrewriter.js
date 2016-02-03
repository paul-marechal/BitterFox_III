function StoryRewriter(path='.') {
	var window = Window('Null Editor');
	var buttons = window.addHeader();
	
	// Where art you ?
	window.setLocation(path);
	
	// Rewriter
	var textarea = $("<textarea class='rewriter'></textarea>");
	window.content.append(textarea);
	window.rewriter = textarea;
	
	// On recharge bien la page...
	window.onreload = function() {
		window.getContent(window.getLocation());
	};
	
	// Mise à jour de la fenêtre
	window.onupdate = function() {
		window.getContent(window.getPath());
	};
	
	// Mise à jour du textarea
	window.updateRewriter = function(data) {
		if (data.error) return window.reload();
		window.noreload();
		
		textarea.attr('readonly', !data.info.w);
		window.setLocation(data.info.path);
		window.setTitle(data.info.name);
		
		$.get('reader', {read: data.info.path}, function(content) {
			textarea.val(content);
		});
	};
	
	// Bouton d'enregistrement
	buttons.addButton(
		'Save', 
		function() {
			$.post('?m=storyrewriter', {action: 'rewrite', path: window.getLocation(),
			content: textarea.val()}, function(data) {
				
				// On met à jour le textarea
				window.updateRewriter(data);
				
			});
		}, 'rewriter-save');
	
	buttons.addButton(
		'Download',
		function() {
			Download(window.getLocation());
		});
	
	// Récupération du contenu
	window.getContent = function(path) {
		$.post('?m=storyrewriter', {action: 'getContent', path: path}, function(data) {
			
			// On met à jour le textarea
			window.updateRewriter(data);
			
		});
	};
	
	return window;
}