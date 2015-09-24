app.controllers.ticketsEdit = {
	'config': {},
	'form': $('#edit-form'),
	'pUser': $('#edit-form p.user'),
	'pUserSpan': $('#edit-form p.user span'),
	'buttonChange': $('#edit-form p.user button'),
	'inputUserId': $('#edit-form input[name=user_id]'),
	'init': function(app) {

		var me = this;
		me.app = app;

		me.modal = app.modals.userSelect.init(this.app);

		//listeners
		this.modal.container.on('hide.bs.modal', function(e) { me.modalOnHide(e, me) });
		this.buttonChange.on('click', function(e) { me.changeOnClick(e, me) });

		return me;
	},
	'modalOnHide': function(e, me) {
		
		if (me.modal.user) {

			me.inputUserId.val(me.modal.user.id)
			me.pUserSpan.html(me.modal.user.display_name);

			if (me.modal.user.email) {
				me.pUserSpan.append(' &lt;' + me.modal.user.email + '&gt;');
			}
		}
		
	},
	'changeOnClick': function(e, me) {
		this.modal.container.modal('show');
	}

}