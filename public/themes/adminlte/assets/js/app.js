var app = {
	'config': {
        'url': $('meta[name="url"]').attr('content'),
		'api_url': $('meta[name="api"]').attr('content'),
		'url_login': $('meta[name="url"]').attr('content') + '/session/create'
	},
	'modals': {},
	'controllers': {},
	'init': function(route)
	{
		var me = this;

		$.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            statusCode: {
            	401: function() {
            		window.location.replace(me.config.url_login);
            	}
            }
        });

		this.depricated();

    if (typeof window['app']['controllers'][route] != "undefined") {
        this.controller = window['app']['controllers'][route].init(this);
    }

	},
	'depricated': function() {

        //user add/select modal

      $("select.select2-default").select2({minimumResultsForSearch: 8});
      $("select.select2-nosearch").select2({minimumResultsForSearch: -1});

        $('.daterange').daterangepicker();
        $('.daterange-rangeonly').daterangepicker({
            opens: 'left',
            ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
             'This Week': [moment().startOf('week'), moment().endOf('week')],
             'Last Week': [moment().subtract('weeks', 1).startOf('week'), moment().subtract('weeks', 1).endOf('week')],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
          },
        });
        $('.singledate').daterangepicker({
            singleDatePicker: true,
            format: 'MM/DD/YYYY',
            drops: 'up'
        });
        $('.singledatedown').daterangepicker({
            singleDatePicker: true,
            format: 'MM/DD/YYYY',
            drops: 'down'
        });
	}
}
