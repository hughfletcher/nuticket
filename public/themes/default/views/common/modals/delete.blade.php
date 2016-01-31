<div class="modal delete" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-sm">
    	<div class="modal-content">
    		<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Delete <span></span></h4>
			</div>
			<form action="" method="POST" accept-charset="UTF-8">
			<input name="_method" type="hidden" value="DELETE">
            <input name="_token" type="hidden" value="{!! csrf_token() !!}">
            <input name="_redirect" type="hidden" value="{{ URL::current() }}">
			    <div class="modal-body"></div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="btn btn-danger">Delete</button>
			    </div>
			</form>
		</div>
  	</div>
</div>