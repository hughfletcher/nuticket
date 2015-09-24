var app = {
	'config': {
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
        var priFull = [
            {id:1,text:'1 - Business is stopped'},
            {id:2,text:'2 - User is stopped'},
            {id:3,text:'3 - Business is hendered'},
            {id:4,text:'4 - User is hendered'},
            {id:5,text:'5 - Increase productivity/savings'}
        ];
        var priAbbr = [
            {id:1,text:'1'},
            {id:2,text:'2'},
            {id:3,text:'3'},
            {id:4,text:'4'},
            {id:5,text:'5'}
        ];
        //tab linking
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
        } 
        $('.go-show-tab').click(function() {
            $('.nav-tabs a[href="' + $(this).attr('href') + '"]').tab('show')
        })

        
        
        //user add/select modal
        

        $('.select2-priorities').select2({
            minimumResultsForSearch: 8,
            data: priFull
        });
      $("select.select2-default").select2({minimumResultsForSearch: 8});
      $(".status-select").select2({
            multiple: true,
            separator: '-',
            data:[
                {id:'closed',text:'Closed'},
                {id:'open',text:'Open'},
                {id:'new',text:'New'}
            ]
      });
      $(".priority-select").select2({
            multiple: true,
            separator: '-',
            data: priAbbr
      });
      // $(".dept-select").select2({
      //       multiple: true,
      //       separator: '-',
      //       data: [@foreach($depts as $id => $name){id:{{ $id }},text:'{{ $name }}'},@endforeach ]
      // });
        $('#createtime').daterangepicker();
        $('.daterange').daterangepicker({
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