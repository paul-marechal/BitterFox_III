function File(name='<unknown>', info) {
	var file = T.file.clone();
	var size = file.find('.file-size');
	var owner = file.find('.file-owner');
	var filename = file.find('.file-name');
	var context;
	
	// Attributs
	file.name = name;
	file.path = info.path;
	file.info = info;
	
	file.addClass(file.info.r ? file.info.w ? 'rw' : 'r' : 'nope');
	file.attr('type', info.type);
	file.attr('ext', info.ext);
	
	file.getName = function() {return file.name;}
	file.setName = function() {
		file.attr('title', name);
		filename.text(name);
	}
	
	file.setName(name);
	size.text(info.size);
	owner.text(info.owner.name);
	
	file.exec = function() {
		var ext = info.ext;
		if (ext in PROGRAMS) W[PROGRAMS[ext]](info.path).pop();
		else W[PROGRAMS['<default>'][info.type]](info.path).pop();
	};
	
	file.open = function() {
		StoryRewriter(info.path).pop();
	};
	
	file.download = function() {
		Download(info.path);
	};
	
	return file;
}

function Dora(path='.') {
	var window = Window();
	var files = window.content;
	var cursors = T.cursors.clone();
	var folder_cursor = cursors.find('.folderCursor');
	var file_cursor = cursors.find('.fileCursor');
	var buttons = window.addHeader();
	files.html(cursors);
	var selected = {};
	
	// Attributs
	window.selected = selected;
	
	// Contexte sur la liste de fichiers
	var context = addContextMenu(window.content);
	
	// On règle l'appli
	window.app('Dora');
	
	// Where art you ?
	window.setLocation(path);
	
	// On clear tout propre
	window.resetFiles = function() {
		window.selected = selected = {};
		files.find('.file').remove();
	};
	
	window.moveTo = function(file) {
		if (file.info.type == 'folder') {
			window.getFiles(file.path);
		} else {
			file.exec();
		}
	};
	
	// On recharge bien la page...
	window.onreload = function() {
		window.getFiles(window.getLocation());
	};
	
	window.update = function() {
		window.getFiles(window.getPath());
	};
	
	window.newFolder = function() {
		var modal = FireCatcher('Entrez un nom de dossier:');
		var name = modal.addInput();
		var buttons = modal.addButtons();
		var ok = buttons.addButton('Ok', function(overlay) {
			var path = window.getLocation() + '/' + name.val();
			$.post('?m=dora', {action: 'newFolder', path: path}, function(data) {
				if (data.error) alert('Impossible...');
				else window.update();
				modal.out();
			});
		});
		
		modal.pop();
	};
	
	window.newFile = function() {
		var modal = FireCatcher('Entrez un nom de fichier:');
		var name = modal.addInput();
		var buttons = modal.addButtons();
		var ok = buttons.addButton('Ok', function(overlay) {
			var path = window.getLocation() + '/' + name.val();
			$.post('?m=dora', {action: 'newFile', path: path}, function(data) {
				if (data.error) alert('Impossible...');
				else window.update();
				modal.out();
			});
		});
		
		modal.pop();
	};
	
	window.deleteFile = function(file) {
		var modal = FireCatcher('Voulez-vous vraiment supprimer ce fichier : "' + file.info.path + '" ?');
		var buttons = modal.addButtons();
		
		var oui = buttons.addButton('Oui', function() {
			$.post('?m=dora', {action: 'delete', path: file.info.path}, function(data) {
				if (data.error) alert('Impossible...');
				else window.update();
				modal.out();
			});
		});
		
		var non = buttons.addButton('Non', function() {modal.out();});
		
		modal.pop();
	};
	
	window.unlock = function(file) {
		$.post('?m=dora', {action: 'unlock', path: file.info.path}, function(data) {
			if (data.error) alert('Impossible...');
			else window.update();
		});
	};
	
	// Options contextuelles
	var new_folder = context.addOption('Nouveau Dossier', window.newFolder);
	var new_file = context.addOption('Nouveau Fichier', window.newFile);
	
	// Récupération des fichiers
	window.getFiles = function(path) {
		$.post('?m=dora', {action: 'getFiles', path: path}, function(data) {
			if (data.error) return window.reload();
			window.noreload();
			
			window.setLocation(data.info.path);
			window.setTitle(data.info.name);
			context.setTitle('Dora - ' + window.getTitle());
			
			// Actions sur la fenêtre
			buttons.html(null);
			buttons.addButton('Up', function() {window.getFiles(window.getLocation() + '/..');});
			if (data.info.w) {
				buttons.addButton('Add Folder', window.newFolder);
				buttons.addButton('Add File', window.newFile);
			}
			
			// Peut-on écrire ?
			new_folder.enabled = data.info.w;
			new_file.enabled = data.info.w;
			
			// Boucle de chargement des fichiers
			window.resetFiles();
			$.each(data.files, function(name, info) {
				var file = File(name, info);
				
				// File context
				if (info.r) {
					context = addContextMenu(file);
					context.setTitle(file.name);
					
					context.addOption('Ouvrir', file.exec);
					
					if (info.type == 'file') {
						context.addOption(
							info.w ? 'Modifier' : 'Inspecter', file.open);
						context.addOption('Download', file.download);
					}
					context.addOption('Unlock', function() {
						window.unlock(file);
					});
					context.addOption('Copier');
					if (info.w) {
						context.addOption('Couper');
						context.addOption('Supprimer', function() {
							window.deleteFile(file);
						});
					}
				}
				
				// Selection
				if (file.path in selected)
					file.addClass('selected');
				
				var selection = function() {
					if (keyPressed(KEYS.shift)) {
						file.addClass('selected');
						selected[file.path] = file;
					} else if (keyPressed(KEYS.ctrl)) {
						file.removeClass('selected');
						delete selected[file.path];
					}
				};
				
				// hover(file);
				file.mousedown(selection);
				file.hover(selection);
				
				// Ouverture
				file.dblclick(function() {
					window.moveTo(file)
				});
				
				// Tri
				if (file.info.type == 'folder')
					 folder_cursor.before(file);
				else file_cursor.before(file);
			});
		});
	};
	
	return window;
}