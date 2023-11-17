@extends('layouts.app')

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Upgrade Software') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-sliders mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{url('#')}}"> {{ __('General Settings') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Upgrade Software') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-body p-5">
					<form id="upgrade-form" method="POST" action="{{ route('admin.settings.upgrade.start', ['update_id' => $latest_version['update_id'], 'version' => $latest_version['version']]) }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">
							<div class="col-sm-12 col-md-12">															
								@if ($latest_version['status'])
									<div class="text-center" id="not-installed-info">
										<h1 class="fs-24"><i class="fa-solid fa-box-check fs-24 mr-2 text-cancel"></i> {{ __('New Update is Available') }}</h1>
										<h6 class="fs-13 text-muted mt-4">{{ __('Current installed version') }}: <span class="text-info font-weight-bold">{{ $current_version }}</span></h6>	
										<h6 class="fs-13 text-muted mb-4">{{ __('New available version') }}: <span class="text-info font-weight-bold"> {{ $latest_version['version'] }} </span> </h6>
										<div id="audio-format" role="radiogroup">
											<span  id="webm-format">							
												<div class="radio-control">
													<input type="checkbox" name="concent" class="input-control fs-13" id="concent">
													<label for="concent" class="label-control text-muted fs-13" id="concent-label">{{  __('I confirm that I have read the Update tab in the documentation and will follow all steps there to finish the update') }} - <a class="font-weight-bold text-primary" target="_blank" href="https://openaidavinci.textract.ai/public/documentation/">{{ __('Documentation Link') }}</a></label>
												</div>	
											</span>										
										</div>	
									</div>
									<div id="installed-info">
										<div class="text-center">
											<h1 class="fs-24">{{ __('Update Installation Completed') }}</h1>
											<h6 class="text-success fs-14 font-weight-bold mt-4 mb-5"><span> {{ $latest_version['version'] }} </span> {{ __('version was installed successfully') }}</h6>
											<i class="fa-solid fa-box-check fs-50 text-success"></i>
										</div>
									</div>														
								@else
									<div class="text-center">
										<h1 class="fs-24">{{ __('You have the Latest Version Installed') }}</h1>
										<h6 class="text-success fs-14 font-weight-bold mt-4 mb-5">{{ __('Current version is the latest') }}</h6>
										<i class="fa-solid fa-box-check fs-50 text-success"></i>
									</div>
								@endif								
							</div>
						</div>
						<div class="card-footer text-center border-0 pb-2 pt-5">		
							<span id="processing"><img src="{{ URL::asset('/img/svgs/upgrade.svg') }}" alt=""></span>												
							<button id="upgrade" type="button" class="btn btn-primary">@if ($latest_version['status']) {{ __('Download & Install Upgrade') }} @else	{{ __('Check New Version') }} @endif</button>						
						</div>
					</form>
				</div>
			</div>
 
			<div class="changelogs">
				<h5>{{ __('Changelogs') }}</h5>
				<hr>

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 3.2</span> - <span class="fs-14 font-weight-semibold">14.11.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">OpenAI Text to Speech feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">GCP 101 (65 Neural2; 6 Studio; 30 WaveNet) New Voices added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Both Dalle and Stable Diffusion engines can be set in the subscription plans</span></li>											
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Image view modal details updated</span></li>											
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 3.1</span> - <span class="fs-14 font-weight-semibold">09.11.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">GPT 4 Turbo model added for Templates and Chats</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">GPT 4 Turbo with Vision model added for Templates and Chats</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Dalle-3 and Dalle-3 HD models added for AI Images</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Image Engine selection to Subscription Plans added</span></li>											
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Removed deprecated model - Ada, Babbage, Curie</span></li>											
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Davinci Settings page updated</span></li>											
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Finance Management - Transactions page table issue fix</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 3.0</span> - <span class="fs-14 font-weight-semibold">06.11.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Add subscription plan manually by admin added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Show hidden plans for select users feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Free trial days feature added for subscription plans</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Update user general and prepaid balances (increase/decrease)</span></li>						
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">User list - personal user information view page updated</span></li>						
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Total active subscribers are visible for each subscription plan</span></li>						
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Users can apply to free trial subscription plan only once</span></li>						
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Free trial subscription process improved, no need to select payment gateways anymore</span></li>						
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Pricing table feature repetition fix</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 2.9</span> - <span class="fs-14 font-weight-semibold">02.11.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat template groups added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat prompts feature added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat (shift + enter) replaced for new line</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat mobile responsiveness improved</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat info banners updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat generated url links are clickable now</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat code view updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Contact us reCaptcha v3 feature updated</span></li>							
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Private API key enforcement added when enabled</span></li>							
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Unlimited words support for free tier user added</span></li>							
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Subscription plans unlimited validation fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Contact us disabling via admin panel fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Voiceover issue fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Documents - Workbooks change issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Youtube social settings missing fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Chat new conversation fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Chat cyrillic words display fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Image creation date fix</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 2.8</span> - <span class="fs-14 font-weight-semibold">16.10.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Iyzico payment gateway added for prepaid/lifetime plans</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Unlimited words/images/characters/minutes option added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Use your own personal Openai API key feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Use your own Stable Diffusion API key feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">About Us page added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Contact Us page added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Frontend - update How it Works section via admin panel</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Frontend - update AI Tools section via admin panel</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Frontend - update Features section via admin panel</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Macedonian language added to templates</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat conversation search option added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat assistant info line added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat voice input added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat code format highlighter</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat stop button added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Macedonian language added to templates</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Language selection added to top at frontend</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Language selection added to Login and Registration pages</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Update page view updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">All missing translation words added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">All disabled AI features are disabled everywhere</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Personal API features added to the subscription plans</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Stripe complete redesign with official PHP SDK</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Table sorting for dates updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Home page blog posts listed based on created date</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Twitter logos updates, youtube social media link added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat input field changed to textarea to support larger text inputs</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Frontend blogs posts order changed to descending order</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">CSS issue in the AI Images fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Stripe for India issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Cron task credit renewal issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Download button in the transcribe result page issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-5">
						<span class="version-name">{{ __('Version') }} 2.7</span> - <span class="fs-14 font-weight-semibold">04.09.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Image creation page redesigned</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Document view images page redesigned</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Negative prompt added for Stable Diffusion</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">CFG Scale (prompt strength) added for Stable Diffusion</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Diffusion Steps added for Stable Diffusion</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Independent models for AI Chat at subscription plan creation added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">All spelling errors fixed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Count Chinese and Japanese words improved</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Performance of all select dropdowns improved</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Promocode for lifetime checkout page missing fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Twitter login improved</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">
 
				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.6</span> - <span class="fs-14 font-weight-semibold">23.08.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Frontend layout redesigned</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Image SDXL v1.0 support added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Set default template language via user profile page added</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Paddle webhook improved</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Yookassa issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.5</span> - <span class="fs-14 font-weight-semibold">24.07.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Paddle payment gateway added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Yookassa payment gateway added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Team members feature updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Templates max word length accuracy updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Paypal webhook updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Ai Voiceover css mobile alignments improved</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">User dashboard favorite templates & chats views fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.4</span> - <span class="fs-14 font-weight-semibold">16.07.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Team Member feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Flutter payment gateway added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Midtrans payment added</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Template user notifications fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Youtube tag generator template fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Instagram hashtag generator template fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Meta description generator template fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Dashboard setting templates as favority issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Affiliate feature not visible in the side menu when disabled improved</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Voiceover delete synthesize result fix</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.3</span> - <span class="fs-14 font-weight-semibold">02.07.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Template text streaming feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Stable diffusion SDXL v0.9 engine added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">OpenAI GPT 3.5 Turbo 16K model added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">New tag added for original and custom templates</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">15 New Languages: Tamil (Malaysia), Persian (Iran), English (UK), Slovak, Latvian, Albanian, Filipino, Khmer (Cambodia), Bangla, Bengali (India), Welsh, Catalan, Serbian, Maltese, Irish added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Text Extender templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Newsletter Generator templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Ad Headlines templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Brand Names templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Promise–Picture–Proof–Push (PPPP) Framework templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Before–After–Bridge (BAB) Framework templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Attention-Interest-Desire-Action (AIDA) Framework templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Brand/Product Press Release templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Company Press Release templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Clickbait Titles templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Terms and Conditions templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Privacy Policy templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Dictionary templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Amazon Product Features templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Tone Changer templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">App and SMS Notifications templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">LinkedIn Ad Descriptions templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">LinkedIn Ad Headlines templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">TitTok Video Scripts templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Twitter Tweets templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Product Characteristics templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Product Comparisons templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Selling Product Titles templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Product Benefits templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Email Subject Lines templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Company Bio templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">LinkedIn Posts templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Business Ideas templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Song Lyrics templates added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Rewrite with Keywords templates added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Voiceover characters view feature updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">User credits view updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Side menu panel updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Ai Voiceover result view audio play bar fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Saving stable diffusion main api key issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Dark mode payment tables css issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.2</span> - <span class="fs-14 font-weight-semibold">04.06.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Multi API keys for Openai added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Multi API keys for Stable Diffusion added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Live Chat (tawk.to) feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Sensitive words filtering feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat search feature added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Languages list scrolling updated</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.1</span> - <span class="fs-14 font-weight-semibold">01.06.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Dark mode added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Template search feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Custom category description added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Templates page updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Ai Chat first response text missing fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 2.0</span> - <span class="fs-14 font-weight-semibold">28.05.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Stable Diffusion for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Image Lightning style added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Image Artist selection added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Export user email list as csv/excel/pdf added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">User dashboard updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Favorite AI Chats added to dashboard</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Promocodes updated, now supports 100% discounts</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Custom chat avatar issues fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">CRON task daily value fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">User profile subscription status text issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.9</span> - <span class="fs-14 font-weight-semibold">18.05.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Template package system updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Ai Chat bot package system updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Ai Chat bot css styling issues fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Ai Speech to Text file type blocking issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.8</span> - <span class="fs-14 font-weight-semibold">17.05.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">42 AI Chat Bots added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Unlimited Custom AI Chat Bots creation feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Premium/Standard/Free tier categories for templates and chats added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Ai Code feature updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Ai Speech to Text feature updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">CRON task for yearly/lifetime credit processing updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Add default credits to manually created users updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Enable/Disabling various features via subscription plan updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Referral system BankTransfer payment fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Referral system payout buttons fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Speech to Text AWS and Wasabi storage issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.7</span> - <span class="fs-14 font-weight-semibold">23.04.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Lifetime plans added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Promocodes for Prepaid Plans and Lifetime Plans added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">User Account deletion feature added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Voiceover symlink dependency removed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">User Subscription table is separated from Transactions table in menu</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Voiceover AWS storage issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Chat is visible to user and subscribers</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Speech to Text storage issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Speech to Text minutes balance counter issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">BankTransfer approval issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.6</span> - <span class="fs-14 font-weight-semibold">19.04.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Davinci Settings: STT controls added and enabling Ai Code and AI Voiceover updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Adding characters and minutes via admin panel added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Webhooks support for characters and minutes updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Chat download buttons added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Free characters and minutes are added during registration updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Default Language and Voice is added to new user upon registration updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Registration server error issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Chat and AI Voiceover are visible to users as well fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">AI Chat stop if user runs out of characters fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Characters and Minutes are visible in Prepaid plans fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.5</span> - <span class="fs-14 font-weight-semibold">17.04.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Chat system added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Speech to Text with OpenAI Whisper added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Voiceover powered by Azure and GCP added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Ability to merge up to 20 AI voices for synthesize task option added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Generate up to 100K synthesize task option added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Voices customization option added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">All documents section updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Subscriptions plan creation updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Razorpay webhook issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Paypal prepaid issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Bank Transfer referral - payment cancellation issue fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.4</span> - <span class="fs-14 font-weight-semibold">26.03.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">GPT 4 (8K/32K) model added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Support for Thai, Bulgarian, Lithuanian, Ukrainian added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Amason S3 storage for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Wasabi Cloud storage for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">AI Code feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Art Styles feature for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Art  Medium feature for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Art Mood feature for AI Images added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Custom category creation feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Custom template editor feature added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Language file en.json with missing words updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Subscription plan cancellation keeps credits until depleted or renewed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Login/Registration page copyright info removed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Login page default credentials removed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Ability to removing .00 in pricing added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Stripe webhook controller updatedStripe webhook controller updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Paypal webhook controller updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Coinbase webhook controller updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Razorpay webhook controller updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Paystack webhook controller updated</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Razorpay prepaid plan payment issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Google 2FA issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Referral payment system tracks payment commissions correctly now</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Copy referral link button fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Recover password page responsiveness fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">User profile shows correct subscription between monthly or yearly now</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.3</span> - <span class="fs-14 font-weight-semibold">09.03.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Unlimited custom templates creation feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">ChatGPT 3.5 Turbo model added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Academic Essay template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Welcome Email template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Cold Email template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Follow up Email template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Creative Stories template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Grammar Checker template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Summarize for 2nd Grader template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Video Scripts template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Amazon Product Description template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Templates filter feature added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Export in Text format added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Result and Template view pages updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Auto save feature for text results removed</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">AI Image results table updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">All Documents table updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Ability to removing .00 in pricing added</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">User default workbook creation issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Workbooks page responsiveness improved</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Login page default credentials removed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Login page responsiveness improved </span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Support email link fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Referral email register link in the email fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.2</span> - <span class="fs-14 font-weight-semibold">21.02.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Support for Portuguese(Brazil), Slovenian, Vietnamese, Swahili, Romanian added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Problem-Agitate-Solution template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Video Descriptions template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Video Titles template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Youtube Tags Generator template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Instagram Captions template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Instagram Hashtags Generator template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Social Media Post (Personal) template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Social Media Post (Business) template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Facebook Headlines template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Google Ads Headlines template added</span></li>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Google Ads Description template added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Templates styling updated</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Credit view within template and image generation added</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Translation file has been updated with missing words</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Stripe 3D payment issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Bank Transfer payment issue fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Profile view monthly/yearly mix fixed</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Missing icons on some hostings fixed </span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Blogs view excerpt updated, hide html tags fixed</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.1</span> - <span class="fs-14 font-weight-semibold">16.02.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Backend side menu user UX update</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Redirect users to Templates page</span></li>
							<li><span class="version-update mr-2">Update</span> <span class="text-muted fs-13">Max text result number increase to 4K in Davinci settings</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Workbooks view table fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Subscribe button in user dashboard route fixSubscribe button in user dashboard route fix</span></li>
							<li><span class="version-fix mr-2">Fix</span> <span class="text-muted fs-13">Default image credits assignment fix for new registered users</span></li>
						</ul>
					</div>
				</div>

				<hr class="mt-6">

				<div class="changelog">
					<div class="changelog-version mt-6">
						<span class="version-name">{{ __('Version') }} 1.0</span> - <span class="fs-14 font-weight-semibold">13.02.2023</span>
					</div>
					<div class="changelog-description mt-6">
						<ul>
							<li><span class="version-new mr-2">New</span> <span class="text-muted fs-13">Initial Release</span></li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('js/upgrade.js')}}"></script>
@endsection
