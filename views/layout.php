<div id="smart-crud-parent-container">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">CRUD</a></li>
		<li role="presentation"><a href="#export" aria-controls="export" role="tab" data-toggle="tab">Export</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="home">
			<h4><?php echo ucfirst( str_replace( "_", " ", $schema ) );?></h4>
            <div id="crud_msg_error" class="alert alert-danger"></div>
			<div id="smart_crud_container" data-schema="<?php echo $schema;?>">
				<?php echo $content; ?>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="export">
			<form id="smart-crud-edit-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
				<input type="hidden" name="action" value="exportExcel">
				<input type="hidden" name="schema" value="<?php echo $schema;?>">
				<div class="row">
					<div class="col-md-8">
						<div class="col-md-6">
							<div class="form-group">
								<label for="double">From Date </label>
								<input type="text" class="form-control" name="date_from" id="date_from" placeholder="Date From"  value=""/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="double">To Date </label>
								<input type="text" class="form-control" name="date_to" id="date_to" placeholder="Date To"  value=""/>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<button type="submit" class="btn btn-primary"> Export</button>
							</div>
						</div>

					</div>
				</div>
			</form>
		</div>
	</div>

</div>
<!--<div class="row">
	<div class="col-md-12">
		<h1>Crud</h1>
		<div id="smart_crud_container" data-schema="<?php /*echo $schema;*/?>">
			<?php /*echo $content; */?>
		</div>
	</div>
</div>-->