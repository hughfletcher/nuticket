app.controllers.ticketsCreate = {
	'config': {},
	'form': $('#create-form'),
	'pUser': $('#create-form p.user'),
	'pUserSpan': $('#create-form p.user span'),
	'inputDisplayName': $('#create-form input[name=display_name]'),
	'inputEmail': $('#create-form input[name=email]'),
	'buttonChange': $('#create-form p.user button'),
	'inputUserId': $('#create-form input[name=user_id]'),
	'selectOrg': $('select.org', '#create-form'),
	'init': function(app) {

		var me = this;
		me.app = app;

		me.modal = app.modals.userSelect.init(this.app);
		me.initUser();



		//listeners
		this.modal.container.on('hide.bs.modal', function(e) { me.modalOnHide(e, me) });
		this.buttonChange.on('click', function(e) { me.changeOnClick(e, me) });

		return me;
	},
	'initUser': function() {

		console.log(this.inputUserId.val());
		if (!this.inputUserId.val()) {
			this.modal.container.modal('show');
			return;
		}
	},
	'modalOnHide': function(e, me) {

		if (me.modal.user) {

			me.inputDisplayName.addClass('hide').prop("readonly", true);
			me.inputEmail.prop("readonly", true).parents('div.form-group').addClass('hide');
			me.pUser.removeClass('hide');

			me.inputUserId.val(me.modal.user.id)
			me.pUserSpan.html(me.modal.user.display_name);
			me.selectOrg.select2('val', me.modal.user.org_id);

			if (me.modal.user.email) {
				me.pUserSpan.append(' &lt;' + me.modal.user.email + '&gt;');
			}
		}

	},
	'changeOnClick': function(e, me) {
		this.modal.container.modal('show');
	}

}
