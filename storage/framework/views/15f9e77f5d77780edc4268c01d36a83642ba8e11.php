

<?php $__env->startSection('css'); ?>
	<!-- Data Table CSS -->
	<link href="<?php echo e(URL::asset('plugins/datatable/datatables.min.css')); ?>" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('API Keys Manager')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.davinci.dashboard')); ?>"> <?php echo e(__('Davinci Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="#"> <?php echo e(__('Davinci Settings')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('API Keys')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
	<div class="row">
		<div class="col-lg-8 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Openai and Stable Diffusion API Keys')); ?></h3>
					<a href="<?php echo e(route('admin.davinci.configs.keys.create')); ?>" id="createButton" class="btn btn-primary text-right right"><?php echo e(__('Store New')); ?></a>
				</div>
				<div class="card-body pt-2">
					<!-- BOX CONTENT -->
					<div class="box-content">
						<!-- SET DATATABLE -->
						<table id='allTemplates' class='table' width='100%'>
								<thead>
									<tr>									
										<th width="5%"><?php echo e(__('Engine')); ?></th> 					
										<th width="15%"><?php echo e(__('API Key')); ?></th> 					
										<th width="3%"><?php echo e(__('Status')); ?></th> 					
										<th width="3%"><?php echo e(__('Created On')); ?></th>	    										 						           	
										<th width="5%"><?php echo e(__('Actions')); ?></th>
									</tr>
								</thead>
						</table> <!-- END SET DATATABLE -->
					</div> <!-- END BOX CONTENT -->

					<div class="col-md-12 col-sm-12 text-center mb-2">
						<a href="<?php echo e(route('admin.davinci.configs')); ?>" class="btn btn-cancel"><?php echo e(__('Return')); ?></a>
					</div>	
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<!-- Data Tables JS -->
	<script src="<?php echo e(URL::asset('plugins/datatable/datatables.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('plugins/sweetalert/sweetalert2.all.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";

			// INITILIZE DATATABLE
			var table = $('#allTemplates').DataTable({
				"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
				responsive: {
					details: {type: 'column'}
				},
				colReorder: true,
				language: {
					"emptyTable": "<div><img id='no-results-img' src='<?php echo e(URL::asset('img/files/no-result.png')); ?>'><br><?php echo e(__('No API Keys stored yet')); ?></div>",
					search: "<i class='fa fa-search search-icon'></i>",
					lengthMenu: '_MENU_ ',
					paginate : {
						first    : '<i class="fa fa-angle-double-left"></i>',
						last     : '<i class="fa fa-angle-double-right"></i>',
						previous : '<i class="fa fa-angle-left"></i>',
						next     : '<i class="fa fa-angle-right"></i>'
					}
				},
				pagingType : 'full_numbers',
				processing: true,
				serverSide: true,
				ajax: "<?php echo e(route('admin.davinci.configs.keys')); ?>",
				columns: [
					{
						data: 'engine-name',
						name: 'engine-name',
						orderable: true,
						searchable: true
					},	
					{
						data: 'api_key',
						name: 'api_key',
						orderable: true,
						searchable: true
					},	
					{
						data: 'status',
						name: 'status',
						orderable: true,
						searchable: true
					},							
					{
						data: 'created-on',
						name: 'created-on',
						orderable: true,
						searchable: true
					},									
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					},
				]
			});

			
			// UPDATE NAME
			$(document).on('click', '.editButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Update API Key')); ?>',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Update')); ?>',
					reverseButtons: true,
					input: 'text',
				}).then((result) => {
					if (result.value) {
						var formData = new FormData();
						formData.append("name", result.value);
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'keys/update',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('API Key Update')); ?>', '<?php echo e(__('API Key has been successfully updated')); ?>', 'success');
									$("#allTemplates").DataTable().ajax.reload();
								} else {
									Swal.fire('<?php echo e(__('Update Error')); ?>', '<?php echo e(__('API Key was not updated correctly')); ?>', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Update Error', data.responseJSON['error'], 'error');
							}
						})
					} else if (result.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('<?php echo e(__('No API Key Entered')); ?>', '<?php echo e(__('Make sure to provide a new api key before updating')); ?>', 'error')
					}
				})
			});


			// DELETE PLAN
			$(document).on('click', '.deleteButton', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '<?php echo e(__('Confirm API Key Deletion')); ?>',
					text: '<?php echo e(__('It will permanently delete this API key')); ?>',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: '<?php echo e(__('Delete')); ?>',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: 'keys/delete',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('<?php echo e(__('API Key Deleted')); ?>', '<?php echo e(__('API Key has been successfully deleted')); ?>', 'success');	
									$("#allTemplates").DataTable().ajax.reload();								
								} else {
									Swal.fire('<?php echo e(__('Delete Failed')); ?>', '<?php echo e(__('There was an error while deleting this API key')); ?>', 'error');
								}      
							},
							error: function(data) {
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});


			// ACTIVATE KEY
			$(document).on('click', '.activateButton', function(e) {

				e.preventDefault();

				var formData = new FormData();
				formData.append("id", $(this).attr('id'));

				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: 'keys/activate',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data == 'active') {
							Swal.fire('<?php echo e(__('API Key Activated')); ?>', '<?php echo e(__('API Key has been activated successfully')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						} else {
							Swal.fire('<?php echo e(__('API Key Deactivated')); ?>', '<?php echo e(__('API Key has been deactivated successfully')); ?>', 'success');
							$("#allTemplates").DataTable().ajax.reload();
						}      
					},
					error: function(data) {
						Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
					}
				})

			});

		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/meetahoe/blockengine.ai/resources/views/admin/davinci/configuration/keys.blade.php ENDPATH**/ ?>