(function() {
	tinymce.create('tinymce.plugins.InsideSocial', {
		init : function(ed, url) {

			var t = this, cm;
			t.editor = ed;

			ed.addCommand('insideSocial', function() {
				ed.windowManager.open({
					file : url + '/config.html',
					width : 450 + parseInt(ed.getLang('insideSocial.delta_width', 0)),
					height : 450 + parseInt(ed.getLang('insideSocial.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
			ed.addButton('insideSocial', {title : 'Inside Social', cmd : 'insideSocial', image: url + '/logo_sm.gif' });
		},
		getInfo : function() {
			return {
				longname : 'InsideSocial',
				author : 'Alan Balasundaram',
				authorurl : 'http://www.insidesoci.al',
				infourl : 'http://www.insidesoci.al',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	tinymce.PluginManager.add('insideSocial', tinymce.plugins.InsideSocial);
})();
