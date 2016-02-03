app.controllers.ticketsShow = {
	'config': {},
	'tabs': $('.nav-tabs'),
	'init': function(app) {

		var me = this;
		me.app = app;

		//tab linking
        var url = document.location.toString();
        if (url.match('#')) {
            $('a[href="#'+url.split('#')[1]+'"]', me.tabs).tab('show') ;
        }
        $('.go-show-tab').click(function() {
            $('a[href="' + $(this).attr('href') + '"]', me.tabs).tab('show')
        })


		return me;
	}

}