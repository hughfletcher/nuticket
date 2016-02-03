app.modals.delete = {
	'container': $('div.modal.delete'),
	'spanTitle': $('div.modal.delete h4 span'),
	'divBody': $('div.modal.delete div.modal-body'),
	'form': $('div.modal.delete form'),
	'init': function(app) {
		this.app = app;
		var me = this;

		return me;
	},
	'show': function(data) {

		this.spanTitle.html(data.title);
		this.divBody.html(data.body);
		this.form.attr('action', data.action);
		this.container.modal('show');
        
    }

}