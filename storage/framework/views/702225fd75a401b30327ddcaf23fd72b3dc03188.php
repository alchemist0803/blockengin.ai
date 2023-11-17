

<?php $__env->startSection('page-header'); ?>
	<!--PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0"><?php echo e(__('Davinci Dashboard')); ?></h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i><?php echo e(__('Admin')); ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.davinci.dashboard')); ?>"> <?php echo e(__('Davinci Management')); ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> <?php echo e(__('Davinci Dashboard')); ?></a></li>
			</ol>
		</div>
	</div>
	<!--END PAGE HEADER -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
	<div class="row">
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Free Words Used')); ?> <span class="text-muted">(<?php echo e(__('Current Month')); ?>)</span></p>
							<h2 class="mb-0"><span class="number-font-chars"><?php echo e(number_format($usage_data['free_current_month'])); ?></span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 fa-solid fa-gifts" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1"><?php echo e(__('Total Free Words Used')); ?> (<?php echo e(__('Current Year')); ?>):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i><?php echo e(number_format($usage_data['free_current_year'])); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Paid Words Used')); ?> <span class="text-muted">(<?php echo e(__('Current Month')); ?>)</span></p>
							<h2 class="mb-0"><span class="number-font-chars"><?php echo e(number_format($usage_data['paid_current_month'])); ?></span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-info fa-solid fa-box-dollar" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1"><?php echo e(__('Total Paid Words Used')); ?> (<?php echo e(__('Current Year')); ?>):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i><?php echo e(number_format($usage_data['paid_current_year'])); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Images Generated')); ?> <span class="text-muted">(<?php echo e(__('Current Month')); ?>)</span></p>
							<h2 class="mb-0"><span class="number-font-chars"><?php echo e($usage_data['images_current_month']); ?></span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-warning fa-solid fa-image-landscape" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1"><?php echo e(__('Total Images Generated')); ?> (<?php echo e(__('Current Year')); ?>):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i><?php echo e($usage_data['images_current_year']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="w-100">
							<p class=" mb-3 fs-12 font-weight-bold"><?php echo e(__('Documents Created')); ?> <span class="text-muted">(<?php echo e(__('Current Month')); ?>)</span></p>
							<h2 class="mb-0"><span class="number-font-chars"><?php echo e($usage_data['contents_current_month']); ?></span></h2>									
						</div>
						<div class="text-right">
							<i class="fs-35 text-success fa-solid fa-folder-open" style="vertical-align: bottom;"></i>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div>
							<span class="text-muted fs-12 mr-1"><?php echo e(__('Total Documents Created')); ?> (<?php echo e(__('Current Year')); ?>):</span>
							<span class="number-font fs-12"><i class="fa fa-bookmark mr-1 text-info"></i><?php echo e($usage_data['contents_current_year']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- CURRENT YEAR USAGE ANALYTICS -->
	<div class="row mt-4">
		<div class="col-lg-12 col-md-12">
			<div class="card mb-4 overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-up mr-4 text-info"></i><?php echo e(__('Words Generated')); ?> <span class="text-muted">(<?php echo e(__('Current Month')); ?>)</span></h3>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div>
								<h3 class="card-title fs-24 font-weight-800"><?php echo e(number_format($total_words_monthly)); ?></h3>
							</div>
							<div class="mb-3">
								<span class="fs-12 text-muted"><?php echo e(__('Total Words Generated During Current Month')); ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-words-monthly" class="h-400"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-lg-12 col-md-12">
			<div class="card mb-4 overflow-hidden border-0">
				<div class="card-header d-inline border-0">
					<div>
						<h3 class="card-title fs-16 mt-3 mb-4"><i class="fa-solid fa-cloud-arrow-up mr-4 text-info"></i><?php echo e(__('Words Generated')); ?> <span class="text-muted">(<?php echo e(__('Current Year')); ?>)</span></h3>
					</div>
					<div>
						<h3 class="card-title fs-24 font-weight-800"><?php echo e(number_format($total_words_yearly)); ?></h3>
					</div>
					<div class="mb-3">
						<span class="fs-12 text-muted"><?php echo e(__('Total Words Generated During Current Year')); ?></span>
					</div>

				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="">
								<canvas id="chart-words-yearly" class="h-400"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- CURRENT YEAR USAGE ANALYTICS -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<!-- Chart JS -->
	<script src="<?php echo e(URL::asset('plugins/chart/chart.min.js')); ?>"></script>
	<script type="text/javascript">
		$(function() {
	
			'use strict';
			
			let usageData = JSON.parse(`<?php echo $chart_data['words_yearly']; ?>`);
			let usageDataset = Object.values(usageData);
			let delayed;

			let ctx = document.getElementById('chart-words-yearly');
			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['<?php echo e(__('Jan')); ?>', '<?php echo e(__('Feb')); ?>', '<?php echo e(__('Mar')); ?>', '<?php echo e(__('Apr')); ?>', '<?php echo e(__('May')); ?>', '<?php echo e(__('Jun')); ?>', '<?php echo e(__('Jul')); ?>', '<?php echo e(__('Aug')); ?>', '<?php echo e(__('Sep')); ?>', '<?php echo e(__('Oct')); ?>', '<?php echo e(__('Nov')); ?>', '<?php echo e(__('Dec')); ?>'],
					datasets: [{
						label: '<?php echo e(__('Words Generated')); ?>',
						data: usageDataset,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.5,
						fill: true
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false,
						labels: {
							display: false
						}
					},
					responsive: true,
					animation: {
						onComplete: () => {
							delayed = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed) {
								delay = context.dataIndex * 50 + context.datasetIndex * 5;
							}
							return delay;
						},
					},
					scales: {
						y: {
							stacked: true,
							ticks: {
								beginAtZero: true,
								font: {
									size: 10
								},
								stepSize: 50000,
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
						x: {
							stacked: true,
							ticks: {
								font: {
									size: 10
								}
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							padding: 15,
							backgroundColor: '#000000',
							titleColor: '#FF9D00',
							yAlign: 'bottom',
							xAlign: 'center',
						},
						legend: {
							position: 'bottom',
							labels: {
								boxWidth: 10,
								font: {
									size: 10
								}
							}
						}
					}
					
				}
			});


			let usageData2 = JSON.parse(`<?php echo $chart_data['words_monthly']; ?>`);
			let usageDataset2 = Object.values(usageData2);
			let delayed2;

			let ctx2 = document.getElementById('chart-words-monthly');
			new Chart(ctx2, {
				type: 'bar',
				data: {
					labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
					datasets: [{
						label: '<?php echo e(__('Words Generated')); ?>',
						data: usageDataset2,
						backgroundColor: '#007bff',
						borderWidth: 1,
						borderRadius: 20,
						barPercentage: 0.5,
						fill: true
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false,
						labels: {
							display: false
						}
					},
					responsive: true,
					animation: {
						onComplete: () => {
							delayed2 = true;
						},
						delay: (context) => {
							let delay = 0;
							if (context.type === 'data' && context.mode === 'default' && !delayed2) {
								delay = context.dataIndex * 50 + context.datasetIndex * 5;
							}
							return delay;
						},
					},
					scales: {
						y: {
							stacked: true,
							ticks: {
								beginAtZero: true,
								font: {
									size: 10
								},
								stepSize: 30000,
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
						x: {
							stacked: true,
							ticks: {
								font: {
									size: 10
								}
							},
							grid: {
								color: '#ebecf1',
								borderDash: [3, 2]                            
							}
						},
					},
					plugins: {
						tooltip: {
							cornerRadius: 10,
							padding: 15,
							backgroundColor: '#000000',
							titleColor: '#FF9D00',
							yAlign: 'bottom',
							xAlign: 'center',
						},
						legend: {
							position: 'bottom',
							labels: {
								boxWidth: 10,
								font: {
									size: 10
								}
							}
						}
					}
					
				}
			});
		});		
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/meetahoe/blockengine.ai/resources/views/admin/davinci/dashboard/index.blade.php ENDPATH**/ ?>