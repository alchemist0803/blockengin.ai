

<?php $__env->startSection('content'); ?>
<div class="container-fluid justify-content-center">
    <div class="row h-100vh align-items-center background-white">
        <div class="col-md-6 col-sm-12 h-100" id="login-responsive">                
            <div class="card-body pr-10 pl-10 pt-10"> 
                
                <div class="dropdown header-locale" id="frontend-local-login">
                    <a class="icon" data-bs-toggle="dropdown">
                        <span class="fs-12 mr-4"><i class="fa-solid text-black fs-16 mr-2 fa-globe"></i><?php echo e(ucfirst(Config::get('locale')[App::getLocale()]['code'])); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                        <div class="local-menu">
                            <?php $__currentLoopData = Config::get('locale'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($lang != App::getLocale()): ?>
                                    <a href="<?php echo e(route('locale', $lang)); ?>" class="dropdown-item d-flex">
                                        <div class="text-info"><i class="flag flag-<?php echo e($language['flag']); ?> mr-3"></i></div>
                                        <div>
                                            <span class="font-weight-normal fs-12"><?php echo e($language['display']); ?></span>
                                        </div>
                                    </a>                                        
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-center font-weight-bold mb-8"><?php echo e(__('Welcome to')); ?> <span class="text-info"><?php echo e(config('app.name')); ?></span></h3>
                
                <form method="POST" action="<?php echo e(route('verification.send')); ?>" id="verify-email">
                    <?php echo csrf_field(); ?>                      
                    
                    <?php if(session('status') == 'verification-link-sent'): ?>
                        <div class="alert alert-login alert-success mb-8"> 
                            <?php echo e(__('A new verification link has been sent to the email address.')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="mb-6 fs-14">
                        <?php echo e(__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you did not receive the email, we will gladly send you another.')); ?>

                    </div>

                    <div class="form-group mb-0 text-center">                        
                        <button type="submit" class="btn btn-primary mr-2"><?php echo e(__('Resend Verification Email')); ?></button>                                                                         
                    </div>
                
                </form>
                
                <div class="text-center">
                    <p class="fs-10 text-muted mt-2">or <a class="text-info" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><?php echo e(__('Logout')); ?></a></p> 
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>

            </div>      
        </div>

        <div class="col-md-6 col-sm-12 text-center background-special h-100 align-middle p-0" id="login-background">
            <div class="login-bg">
                <img src="<?php echo e(URL::asset('img/frontend/backgrounds/login.webp')); ?>" alt="">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tasks\blockengin.ais\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>