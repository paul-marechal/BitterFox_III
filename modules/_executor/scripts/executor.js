function Executor(path) {
	$.post('?n=executor', {action: 'script', path: path}, function(data) {
		console.log(data);
	});
	
	return {pop: function() {}};
};