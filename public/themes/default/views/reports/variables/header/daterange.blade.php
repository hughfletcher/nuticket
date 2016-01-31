<form action="{{ route('report.index', [$slug]) }}" class="form-inline pull-right">
	<div class="input-group">
		<input type="text" name="{{ $name }}" value="{{ $value }}" class="form-control input-sm daterange">
		<div class="input-group-btn">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-calendar"></i></button>
		</div>
	</div>
</form>