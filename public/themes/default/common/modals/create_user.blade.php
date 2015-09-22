<div class="modal add_user" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-user"></i> Search or create a user</h4>
				</div>
				<form action="" method="post" enctype="text/plain">
					<input name="source" type="hidden" value="">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input name="user_id" type="text" class="form-control select2-users input-sm search" placeholder="Search for a user">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="first_name" class="control-label">First Name</label>
									<input class="form-control input-sm" name="first_name">
									<span class="help-block hide"></span>
								</div>
								<div class="form-group">
									<label for="username" class="control-label">User Name</label>
									<input class="form-control input-sm" name="username">
									<span class="help-block hide"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="lastname" class="control-label">Last Name</label>
									<input class="form-control input-sm" name="last_name">
									<span class="help-block hide"></span>
								</div>
								<div class="form-group">
									<label for="email" class="control-label">Email</label>
									<input class="form-control input-sm" name="email">
									<span class="help-block hide"></span>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer clearfix">

						<button type="button" class="btn pull-left" data-dismiss="modal"> Cancel</button>
						<button type="button" class="btn pull-left clear"> Clear</button>

						<button type="button" class="btn btn-primary"  data-loading-text="Adding..."> Add User</button>
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>