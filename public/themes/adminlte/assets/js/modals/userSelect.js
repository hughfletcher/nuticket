app.modals.userSelect = {
	'container': $('.modal.add_user'),
	'inputSearch' : $('.select2-users', '.modal.add_user'),
	'buttonClear': $('button.clear', '.modal.add_user'),
	'form': $('form', '.modal.add_user')[0],
	'inputs': $( "input", '.modal.add_user'),
	'selectOrg': $( "select.org", '.modal.add_user'),
	'results': [],
	'buttonPrimary': $('button.btn-primary', '.modal.add_user'),
	'user': null,
	'init': function(app) {
		this.app = app;
		var me = this;

		//plugins
		me.initSearch();

		//listeners
		me.container.on('hidden.bs.modal', function(e) { me.resetForm(e, me) });
		me.inputSearch.on("change", function(e) { me.searchOnChange(e, me) });
		me.buttonClear.on("click", function(e) { me.resetForm(e, me) });
		me.buttonPrimary.on("click", function(e) { me.primaryOnClick(e, me) });

		return me;
	},
	'initSearch': function() {
		var me = this;

		me.inputSearch.select2({
			minimumInputLength: 2,
	        cache: true,
	        ajax: {
	            url: this.app.config.api_url + '/users?fields=id,display_name,first_name,last_name,email,username,org_id,source',
	            dataType: 'json',
	            data: function (term) {
	                return {
	                    q: term // search term
	                };
	            },
	            results: function(data) {
	                me.results = data;
	                var results = [];
	                $.each(data, function(index, item){
	                    results.push({
	                        id: item.id,
	                        text: item.display_name
	                    });
	                });
	                return {
	                    results: results
	                };
	            }
	        }
		});
        
    },
    'searchOnChange': function(e, me) {

        $.each(me.results, function(index, row){
            if (e.val == row.id) {
            	var nolocal = row.source != 'local' ? true : false;
                $( "input[name=first_name]" ).val(row.first_name).prop("readonly", nolocal);
                $( "input[name=last_name]" ).val(row.last_name).prop("readonly", nolocal);
                $( "input[name=username]" ).val(row.username).prop("readonly", nolocal);
                $( "input[name=email]", '.modal.add_user').val(row.email).prop("readonly", nolocal);
                $( "input[name=_method]" ).val(row.id % 1 === 0 ? 'PUT' : 'POST');
                $( "input[name=source]" ).val(!nolocal ? 'local' : row.source);
                if(row.org_id) {
                	me.selectOrg.select2('val', row.org_id).select2("readonly", nolocal);
                }
                $('button.btn-primary', me.container).html('Continue');
            }
        });
    },
	'resetForm': function(e, me) {
        me.form.reset();
        me.inputSearch.select2('val', null);
        me.inputs.prop('readonly', false);
        me.buttonPrimary.html('Add User');
        $('div.form-group', '.modal.add_user').removeClass('has-error');
        $('span.help-block', '.modal.add_user').addClass('hide');
        me.selectOrg.select2('val', '').select2("readonly", false);
	},
	'primaryOnClick': function(e, me) {

		var id = '';
		if (me.inputSearch.select2('val') % 1 === 0) {

			id = '/' + me.inputSearch.select2('val');

		}

		me.buttonPrimary.button('loading');
		
		$.post(me.app.config.api_url + '/users' + id, $(me.form).serialize(), function(data) { me.userAddSuccess(data, me) }, 'json')
			.fail(me.userAddFail)
			.always(function() {
				me.buttonPrimary.button('reset');
			});

	},
	'userAddSuccess': function(data, me) {
		me.user = data;
		me.container.modal('hide');
	},
	'userAddFail': function(xhr) {

		if (xhr.status == 422) {
			$.each($.parseJSON(xhr.responseText), function(i, r) {
				var ele = $('input[name=' + i + ']');
				ele.parent('div.form-group').addClass('has-error');
				ele.next('span.help-block').removeClass('hide').html(r[0]);
			});
		} else {

		}
	}

}