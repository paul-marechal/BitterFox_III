var T = {};

// Chargement des templates auprès d'OpenDoor
function loadTemplates() {
	$.ajax({
		url: '?m=templates',
		success: function(data) {
			$.each(data, function(k, v) {T[k] = $(v);});
		},
		error: function(data) {
			console.log('Failed to load templates');
		},
		timeout: 100,
		async: false,
	});
}

loadTemplates();