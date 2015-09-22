app.modals.userSelect = {
	'container': $('.modal.add_user'),
	'inputSearch' : $('.select2-users', '.modal.add_user'),
	'buttonClear': $('button.clear', '.modal.add_user'),
	'form': $('form', '.modal.add_user')[0],
	'inputs': $( "input", '.modal.add_user'),
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
	            url: this.app.config.api_url + '/users?fields=id,display_name,first_name,last_name,email,username',
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
                $( "input[name=first_name]" ).val(row.first_name).prop("readonly", true);
                $( "input[name=last_name]" ).val(row.last_name).prop("readonly", true);
                $( "input[name=username]" ).val(row.username).prop("readonly", true);
                $( "input[name=email]" ).val(row.email).prop("readonly", true);
                $( "input[name=source]" ).val(row.source);
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
	},
	'primaryOnClick': function(e, me) {

		if ($( "input[name=source]" ).val() == 'local') {
			
			$.each(me.results, function(index, row){
	            if (me.inputSearch.select2("val") == row.id) {
	            	me.user = row;
	            }
        	});
			
			me.container.modal('hide');
			return;
		}

		me.buttonPrimary.button('loading');
		
		$.post(me.app.config.api_url + '/users', $(me.form).serialize(), function(data) { me.userAddSuccess(data, me) }, 'json')
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