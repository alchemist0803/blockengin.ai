

<?php $__env->startSection('page-header'); ?>
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Subscription Plans')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('user.dashboard')); ?>"><i class="fa-solid fa-box-circle-check mr-2 fs-12"></i><?php echo e(__('User')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(route('user.plans')); ?>"> <?php echo e(__('Pricing Plans')); ?></a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
	<div class="card border-0 pt-2">
		<div class="card-body">			
			
			<?php if($monthly || $yearly || $prepaid || $lifetime): ?>

				<div class="tab-menu-heading text-center">
					<div class="tabs-menu dark-theme-target" >								
						<ul class="nav">
							<?php if($prepaid): ?>						
								<li><a href="#prepaid" class="<?php if(!$monthly && !$yearly && $prepaid && !$lifetime): ?> active <?php else: ?> '' <?php endif; ?>" data-bs-toggle="tab"> <?php echo e(__('Prepaid Plans')); ?></a></li>
							<?php endif; ?>							
							<?php if($monthly): ?>
								<li><a href="#monthly_plans" class="<?php if(($monthly && $prepaid && $yearly) || ($monthly && !$prepaid && !$yearly) || ($monthly && $prepaid && !$yearly) || ($monthly && !$prepaid && $yearly)): ?> active <?php else: ?> '' <?php endif; ?>" data-bs-toggle="tab"> <?php echo e(__('Monthly Plans')); ?></a></li>
							<?php endif; ?>	
							<?php if($yearly): ?>
								<li><a href="#yearly_plans" class="<?php if((!$monthly && !$prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && $lifetime)): ?>  active <?php else: ?> '' <?php endif; ?>" data-bs-toggle="tab"> <?php echo e(__('Yearly Plans')); ?></a></li>
							<?php endif; ?>
							<?php if($lifetime): ?>
								<li><a href="#lifetime" class="<?php if((!$monthly && !$yearly && !$prepaid &&  $lifetime) || (!$monthly && !$yearly && $prepaid &&  $lifetime)): ?> active <?php else: ?> '' <?php endif; ?>" data-bs-toggle="tab"> <?php echo e(__('Lifetime Plans')); ?></a></li>
							<?php endif; ?>								
						</ul>
					</div>
				</div>

			

				<div class="tabs-menu-body">
					<div class="tab-content">

						<?php if($prepaid): ?>
							<div class="tab-pane <?php if((!$monthly && $prepaid) && (!$yearly && $prepaid)): ?> active <?php else: ?> '' <?php endif; ?>" id="prepaid">

								<?php if($prepaids->count()): ?>
													
									<div class="row justify-content-md-center">
									
										<?php $__currentLoopData = $prepaids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prepaid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="price-card pl-3 pr-3 pt-2 mb-6">
													<div class="card p-4 pl-5 prepaid-cards <?php if($prepaid->featured): ?> price-card-border <?php endif; ?>">
														<?php if($prepaid->featured): ?>
															<span class="plan-featured-prepaid"><?php echo e(__('Most Popular')); ?></span>
														<?php endif; ?>
														<div class="plan prepaid-plan">
															<div class="plan-title"><?php echo e($prepaid->plan_name); ?> </div>
															<div class="plan-cost-wrapper mt-2 text-center mb-3 p-1"><span class="plan-cost"><?php if(config('payment.decimal_points') == 'allow'): ?> <?php echo e(number_format((float)$prepaid->price, 2)); ?> <?php else: ?> <?php echo e(number_format($prepaid->price)); ?> <?php endif; ?></span><span class="prepaid-currency-sign text-muted"><?php echo e($prepaid->currency); ?></span></div>
															<p class="fs-12 mb-3 text-muted"><?php echo e(__('Included Credits')); ?></p>	
															<div class="credits-box">
																<?php if($prepaid->words != 0): ?> <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e(__('Words Included')); ?>: <span class="ml-2 font-weight-bold text-primary"><?php echo e(number_format($prepaid->words)); ?></span></p><?php endif; ?>
																 <?php if($prepaid->images != 0): ?> <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e(__('Images Included')); ?>: <span class="ml-2 font-weight-bold text-primary"><?php echo e(number_format($prepaid->images)); ?></span></p><?php endif; ?>
																 <?php if($prepaid->characters != 0): ?> <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e(__('Characters Included')); ?>: <span class="ml-2 font-weight-bold text-primary"><?php echo e(number_format($prepaid->characters)); ?></span></p><?php endif; ?>																							
																 <?php if($prepaid->minutes != 0): ?> <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e(__('Minutes Included')); ?>: <span class="ml-2 font-weight-bold text-primary"><?php echo e(number_format($prepaid->minutes)); ?></span></p><?php endif; ?>	
															</div>
															<div class="text-center action-button mt-2 mb-2">
																<a href="<?php echo e(route('user.prepaid.checkout', ['type' => 'prepaid', 'id' => $prepaid->id])); ?>" class="btn btn-primary-pricing"><?php echo e(__('Select Package')); ?></a> 
															</div>																								                                                                          
														</div>							
													</div>	
												</div>							
											</div>										
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>						

									</div>

								<?php else: ?>
									<div class="row text-center">
										<div class="col-sm-12 mt-6 mb-6">
											<h6 class="fs-12 font-weight-bold text-center"><?php echo e(__('No Prepaid plans were set yet')); ?></h6>
										</div>
									</div>
								<?php endif; ?>

							</div>			
						<?php endif; ?>	

						<?php if($monthly): ?>	
							<div class="tab-pane <?php if(($monthly && $prepaid) || ($monthly && !$prepaid) || ($monthly && !$yearly)): ?> active <?php else: ?> '' <?php endif; ?>" id="monthly_plans">

								<?php if($monthly_subscriptions->count()): ?>		

									<div class="row justify-content-md-center">

										<?php $__currentLoopData = $monthly_subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
													<div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card <?php if($subscription->featured): ?> price-card-border <?php endif; ?>">
														<?php if($subscription->featured): ?>
															<span class="plan-featured"><?php echo e(__('Most Popular')); ?></span>
														<?php endif; ?>
														<div class="plan">			
															<div class="plan-title"><?php echo e($subscription->plan_name); ?></div>																						
															<p class="plan-cost mb-5"><?php echo config('payment.default_system_currency_symbol'); ?><?php if(config('payment.decimal_points') == 'allow'): ?><?php echo e(number_format((float)$subscription->price, 2)); ?> <?php else: ?><?php echo e(number_format($subscription->price)); ?> <?php endif; ?><span class="fs-12 text-muted"><span class="mr-1">/</span> <?php echo e(__('per month')); ?></span></p>
															<div class="text-center action-button mt-2 mb-5">
																<?php if(auth()->user()->plan_id == $subscription->id): ?>
																	<a href="#" class="btn btn-primary-pricing"><?php echo e(__('Subscribed')); ?></a> 
																<?php else: ?>
																	<a href="<?php echo e(route('user.plan.subscribe', $subscription->id)); ?>" class="btn btn-primary-pricing"><?php echo e(__('Subscribe Now')); ?></a>
																<?php endif; ?>                                               														
															</div>
															<p class="fs-12 mb-3 text-muted"><?php echo e(__('Included Features')); ?></p>																		
															<ul class="fs-12 pl-3">	
																<?php if($subscription->words == -1): ?>
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li>
																<?php else: ?>	
																	<?php if($subscription->words != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->words); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->images == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->images != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->images); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->minutes == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->minutes != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->minutes); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->characters == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->characters != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->characters); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																	<?php if($subscription->team_members != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->team_members); ?></span> <span class="plan-feature-text"><?php echo e(__('team members')); ?></span></li> <?php endif; ?>
																
																<?php if(config('settings.chat_feature_user') == 'allow'): ?>
																	<?php if($subscription->chat_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Chats Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->image_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Images Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->voiceover_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Voiceover Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->transcribe_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Speech to Text Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.code_feature_user') == 'allow'): ?>
																	<?php if($subscription->code_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Code Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if($subscription->team_members): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('Team Members Option')); ?></span></li> <?php endif; ?>
																<?php $__currentLoopData = (explode(',', $subscription->plan_features)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<?php if($feature): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e($feature); ?></li>
																	<?php endif; ?>																
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>															
															</ul>																
														</div>					
													</div>	
												</div>							
											</div>										
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

									</div>	
								
								<?php else: ?>
									<div class="row text-center">
										<div class="col-sm-12 mt-6 mb-6">
											<h6 class="fs-12 font-weight-bold text-center"><?php echo e(__('No Subscriptions plans were set yet')); ?></h6>
										</div>
									</div>
								<?php endif; ?>					
							</div>	
						<?php endif; ?>	
						
						<?php if($yearly): ?>	
							<div class="tab-pane <?php if(($yearly && $prepaid) && ($yearly && !$prepaid) && ($yearly && !$monthly)): ?> active <?php else: ?> '' <?php endif; ?>" id="yearly_plans">

								<?php if($yearly_subscriptions->count()): ?>		

									<div class="row justify-content-md-center">

										<?php $__currentLoopData = $yearly_subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
													<div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card <?php if($subscription->featured): ?> price-card-border <?php endif; ?>">
														<?php if($subscription->featured): ?>
															<span class="plan-featured"><?php echo e(__('Most Popular')); ?></span>
														<?php endif; ?>
														<div class="plan">			
															<div class="plan-title"><?php echo e($subscription->plan_name); ?></div>																						
															<p class="plan-cost mb-5"><?php echo config('payment.default_system_currency_symbol'); ?><?php if(config('payment.decimal_points') == 'allow'): ?><?php echo e(number_format((float)$subscription->price, 2)); ?> <?php else: ?><?php echo e(number_format($subscription->price)); ?> <?php endif; ?><span class="fs-12 text-muted"><span class="mr-1">/</span> <?php echo e(__('per year')); ?></span></p>
															<div class="text-center action-button mt-2 mb-5">
																<?php if(auth()->user()->plan_id == $subscription->id): ?>
																	<a href="#" class="btn btn-primary-pricing"><?php echo e(__('Subscribed')); ?></a> 
																<?php else: ?>
																	<a href="<?php echo e(route('user.plan.subscribe', $subscription->id)); ?>" class="btn btn-primary-pricing"><?php echo e(__('Subscribe Now')); ?></a>
																<?php endif; ?>                                                														
															</div>
															<p class="fs-12 mb-3 text-muted"><?php echo e(__('Included Features')); ?></p>																	
															<ul class="fs-12 pl-3">		
																<?php if($subscription->words == -1): ?>
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li>
																<?php else: ?>	
																	<?php if($subscription->words != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->words); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->images == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->images != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->images); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->minutes == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->minutes != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->minutes); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->characters == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->characters != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->characters); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																	<?php if($subscription->team_members != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->team_members); ?></span> <span class="plan-feature-text"><?php echo e(__('team members')); ?></span></li> <?php endif; ?>
																
																<?php if(config('settings.chat_feature_user') == 'allow'): ?>
																	<?php if($subscription->chat_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Chats Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->image_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Images Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->voiceover_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Voiceover Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->transcribe_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Speech to Text Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.code_feature_user') == 'allow'): ?>
																	<?php if($subscription->code_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Code Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if($subscription->team_members): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('Team Members Option')); ?></span></li> <?php endif; ?>
																<?php $__currentLoopData = (explode(',', $subscription->plan_features)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<?php if($feature): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e($feature); ?></li>
																	<?php endif; ?>																
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>															
															</ul>																
														</div>					
													</div>	
												</div>							
											</div>											
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

									</div>	
								
								<?php else: ?>
									<div class="row text-center">
										<div class="col-sm-12 mt-6 mb-6">
											<h6 class="fs-12 font-weight-bold text-center"><?php echo e(__('No Subscriptions plans were set yet')); ?></h6>
										</div>
									</div>
								<?php endif; ?>					
							</div>
						<?php endif; ?>	
						
						<?php if($lifetime): ?>
							<div class="tab-pane <?php if((!$monthly && $lifetime) && (!$yearly && $lifetime)): ?> active <?php else: ?> '' <?php endif; ?>" id="lifetime">

								<?php if($lifetime_subscriptions->count()): ?>                                                    
									
									<div class="row justify-content-md-center">
									
										<?php $__currentLoopData = $lifetime_subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>																			
											<div class="col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200" data-aos-once="true" data-aos-duration="400">
												<div class="pt-2 ml-2 mr-2 h-100 prices-responsive pb-6">
													<div class="card p-5 mb-4 pl-7 pr-7 h-100 price-card <?php if($subscription->featured): ?> price-card-border <?php endif; ?>">
														<?php if($subscription->featured): ?>
															<span class="plan-featured"><?php echo e(__('Most Popular')); ?></span>
														<?php endif; ?>
														<div class="plan">			
															<div class="plan-title"><?php echo e($subscription->plan_name); ?></div>																						
															<p class="plan-cost mb-5"><?php echo config('payment.default_system_currency_symbol'); ?><?php if(config('payment.decimal_points') == 'allow'): ?><?php echo e(number_format((float)$subscription->price, 2)); ?> <?php else: ?><?php echo e(number_format($subscription->price)); ?> <?php endif; ?><span class="fs-12 text-muted"><span class="mr-1">/</span> <?php echo e(__('for lifetime')); ?></span></p>
															<div class="text-center action-button mt-2 mb-5">
																<?php if(auth()->user()->plan_id == $subscription->id): ?>
																	<a href="#" class="btn btn-primary-pricing"><?php echo e(__('Subscribed')); ?></a> 
																<?php else: ?>
																	<a href="<?php echo e(route('user.prepaid.checkout', ['type' => 'lifetime', 'id' => $subscription->id])); ?>" class="btn btn-primary-pricing"><?php echo e(__('Subscribe Now')); ?></a>
																<?php endif; ?>                                                 														
															</div>
															<p class="fs-12 mb-3 text-muted"><?php echo e(__('Included Features')); ?></p>																	
															<ul class="fs-12 pl-3">		
																<?php if($subscription->words == -1): ?>
																	<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li>
																<?php else: ?>	
																	<?php if($subscription->words != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->words); ?></span> <span class="plan-feature-text"><?php echo e(__('words / month')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->images == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->images != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->images); ?></span> <span class="plan-feature-text"><?php echo e(__('images / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->minutes == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->minutes != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->minutes); ?></span> <span class="plan-feature-text"><?php echo e(__('minutes / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->characters == -1): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e(__('Unlimited')); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li>
																	<?php else: ?>
																		<?php if($subscription->characters != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->characters); ?></span> <span class="plan-feature-text"><?php echo e(__('characters / month')); ?></span></li> <?php endif; ?>
																	<?php endif; ?>																	
																<?php endif; ?>
																	<?php if($subscription->team_members != 0): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold"><?php echo e($subscription->team_members); ?></span> <span class="plan-feature-text"><?php echo e(__('team members')); ?></span></li> <?php endif; ?>
																
																<?php if(config('settings.chat_feature_user') == 'allow'): ?>
																	<?php if($subscription->chat_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Chats Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.image_feature_user') == 'allow'): ?>
																	<?php if($subscription->image_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Images Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.voiceover_feature_user') == 'allow'): ?>
																	<?php if($subscription->voiceover_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Voiceover Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.whisper_feature_user') == 'allow'): ?>
																	<?php if($subscription->transcribe_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Speech to Text Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if(config('settings.code_feature_user') == 'allow'): ?>
																	<?php if($subscription->code_feature): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('AI Code Feature')); ?></span></li> <?php endif; ?>
																<?php endif; ?>
																<?php if($subscription->team_members): ?> <li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text"><?php echo e(__('Team Members Option')); ?></span></li> <?php endif; ?>
																<?php $__currentLoopData = (explode(',', $subscription->plan_features)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<?php if($feature): ?>
																		<li class="fs-14 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <?php echo e($feature); ?></li>
																	<?php endif; ?>																
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>															
															</ul>																
														</div>					
													</div>	
												</div>							
											</div>											
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>					

									</div>

								<?php else: ?>
									<div class="row text-center">
										<div class="col-sm-12 mt-6 mb-6">
											<h6 class="fs-12 font-weight-bold text-center"><?php echo e(__('No lifetime plans were set yet')); ?></h6>
										</div>
									</div>
								<?php endif; ?>

							</div>	
						<?php endif; ?>	
					</div>
				</div>
			
			<?php else: ?>
				<div class="row text-center">
					<div class="col-sm-12 mt-6 mb-6">
						<h6 class="fs-12 font-weight-bold text-center"><?php echo e(__('No Subscriptions or Prepaid plans were set yet')); ?></h6>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/meetahoe/blockengine.ai/resources/views/user/plans/index.blade.php ENDPATH**/ ?>