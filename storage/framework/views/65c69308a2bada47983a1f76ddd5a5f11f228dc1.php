

<?php $__env->startSection('page-header'); ?>
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Add Subscription')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-id-badge mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.dashboard')); ?>"> <?php echo e(__('User Management')); ?></a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('admin.user.list')); ?>"><?php echo e(__('User List')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Add Subscription')); ?></a></li>
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
					<h3 class="card-title"><?php echo e(__('Assign Subscription Plan')); ?></h3>
				</div>
				<div class="card-body">
					<form method="POST" action="<?php echo e(route('admin.user.assign', [$user->id])); ?>" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						
						<div class="row">

							<div class="col-sm-12 col-md-12">
								<div>
									<p class="fs-14 mb-2"><?php echo e(__('User Full Name')); ?>: <span class="font-weight-bold ml-1"><?php echo e($user->name); ?></span></p>
									<p class="fs-14 mb-2"><?php echo e(__('User Email Address')); ?>: <span class="font-weight-bold ml-1"><?php echo e($user->email); ?></span></p>
									<p class="fs-14 mb-2"><?php echo e(__('Registered On')); ?>: <span class="font-weight-bold ml-1"><?php echo e($user->created_at); ?></span></p>
									<p class="fs-14 mb-4"><?php echo e(__('Current subscription plan of the user')); ?>: <span class="font-weight-bold ml-1"><?php echo e($plan); ?></span></p>
								</div>

								<div class="input-box">
									<label class="form-label fs-14"><?php echo e(__('Subscription Plans')); ?></label>
									<select id="user-role" name="plan" data-placeholder="<?php echo e(__('Select Subscription Plan')); ?>">	
										<?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value=<?php echo e($plan->id); ?>><?php echo e(ucfirst($plan->payment_frequency)); ?> - <?php echo e($plan->plan_name); ?> (<?php echo e($plan->price); ?> <?php echo e($plan->currency); ?>) - <?php echo e(ucfirst($plan->status)); ?> <?php echo e(__('Plan')); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>																			
									</select>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center pr-0">							
							<a href="<?php echo e(route('admin.user.list')); ?>" class="btn btn-cancel mr-2"><?php echo e(__('Return')); ?></a>
							<button type="submit" class="btn btn-primary"><?php echo e(__('Assign')); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/meetahoe/blockengine.ai/resources/views/admin/users/list/subscription.blade.php ENDPATH**/ ?>