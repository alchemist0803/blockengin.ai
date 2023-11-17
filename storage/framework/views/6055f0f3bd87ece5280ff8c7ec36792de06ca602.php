

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Store New API Key')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.davinci.dashboard')); ?>"> <?php echo e(__('Davinci Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="#"> <?php echo e(__('Davinci Settings')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Store API Keys')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>						
	<div class="row">
		<div class="col-lg-6 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><?php echo e(__('Store New API Key')); ?></h3>
				</div>
				<div class="card-body pt-5">									
					<form id="" action="<?php echo e(route('admin.davinci.configs.keys.store')); ?>" method="post" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>

						<div class="row mt-2">							
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="input-box">								
									<h6><?php echo e(__('AI Engine')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="chats" name="engine" class="form-select" data-placeholder="<?php echo e(__('Select AI Engine')); ?>">
										<option value="openai" selected><?php echo e(__('OpenAI')); ?></option>
										<option value="stable_diffusion"><?php echo e(__('Stable Diffusion')); ?></option>																																																																																																									
									</select>
								</div> 
							</div>

							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><?php echo e(__('API Key')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="text" class="form-control" name="api_key" value="<?php echo e(old('api_key')); ?>" required>
									</div> 
									<?php $__errorArgs = ['api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<p class="text-danger"><?php echo e($errors->first('api_key')); ?></p>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>	
								</div> 						
							</div>

							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="input-box">
									<h6><?php echo e(__('Status')); ?> <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<select id="templates-user" name="status" class="form-select" data-placeholder="<?php echo e(__('Set API Key Status')); ?>">
										<option value=1 selected><?php echo e(__('Active')); ?></option>	
										<option value=0><?php echo e(__('Deactive')); ?></option>																																									
									</select>
								</div>
							</div>	
						</div>

						<!-- ACTION BUTTON -->
						<div class="border-0 text-right mb-2 mt-1">
							<a href="<?php echo e(route('admin.davinci.configs.keys')); ?>" class="btn btn-cancel mr-2"><?php echo e(__('Cancel')); ?></a>
							<button type="submit" class="btn btn-primary"><?php echo e(__('Create')); ?></button>							
						</div>				

					</form>					
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/meetahoe/blockengine.ai/resources/views/admin/davinci/configuration/create.blade.php ENDPATH**/ ?>