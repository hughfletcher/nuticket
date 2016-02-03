app.controllers.meTimeIndex = {
	'config': {},
	'aDelete': $('td.tools a.delete'),
	'urlDestroy': app.config.url + '/me/time/',
	'init': function(app) {

		var me = this;
		me.app = app;

		me.delete = app.modals.delete.init(this.app);

		//listeners
		// this.modal.container.on('hide.bs.modal', function(e) { me.modalOnHide(e, me) });
		this.aDelete.on('click', function(e) { return me.showConfirmDelete(e, me) });

		return me;
	},
	'showConfirmDelete': function(e, me) {

		var tr = $(e.target).parents('tr');
		var date = tr.children('td:first-child').html();
		var hours = tr.children('td:nth-child(2)').html();
		var id = $(e.target).parent().data('id');
		
		var data = {
			'title': 'Entry',
			'body': 'Delete entry on ' + date + ' with ' + hours + ' hour(s)?',
			'action': me.urlDestroy + id
		};
		me.delete.show(data);

		return false;
		
	}

}