<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/custom.css">
	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css">
	<!-- Fonts and icons -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet">
	<!-- Reset CSS -->
	<link rel="stylesheet" href="./css/reset.css">
	<!-- Style CSS -->
	<link rel="stylesheet" href="./css/style.css">
	<!-- Responsive  CSS -->
	<link rel="stylesheet" href="./css/responsive.css">
</head>

<body>
	<?php
	require_once('./inc/check.php');
	?>
	<div class="wizard-main">
		<div class="container">
			<!-- /.row -->
			<div class="row">
				<div class="col-md-10 mx-auto">
					<div class="card card-default">
						<div class="card-header bg-dark">
							<h3 class="card-title text-white text-center">Woju Techologies G.U.I Installer</h3>
						</div>
						<div class="card-body p-0">
							<?php if (isset($action) && !empty($action)) : ?>
								<div class="bs-stepper">
									<div class="bs-stepper-header" role="tablist">
										<!-- your steps here -->
										<div class="step" data-target="#server">
											<button type="button" class="step-trigger" role="tab" aria-controls="server" id="server-trigger">
												<span class="bs-stepper-circle" title="Server Requirements"><i class="fas fa-server"></i></span>
												<!-- <span class="bs-stepper-label">Server Requirements</span> -->
											</button>
										</div>
										<!-- steps two -->
										<div class="line"></div>
										<div class="step" data-target="#file">
											<button type="button" class="step-trigger" role="tab" aria-controls="file" id="file-trigger">
												<span class="bs-stepper-circle" title="File Permissions"><i class="fas fa-file"></i></span>
												<!-- <span class="bs-stepper-label">File Permissions</span> -->
											</button>
										</div>
										<!-- step three -->
										<div class="line"></div>
										<div class="step" data-target="#database">
											<button type="button" class="step-trigger" role="tab" aria-controls="database" id="database-trigger">
												<span class="bs-stepper-circle" title="Database Information"><i class="fas fa-database"></i></span>
												<!-- <span class="bs-stepper-label">Database Information</span> -->
											</button>
										</div>
										<div class="line"></div>
										<div class="step" data-target="#success">
											<button type="button" class="step-trigger" role="tab" aria-controls="success" id="success-trigger">
												<span class="bs-stepper-circle" title="Complete Installation"><i class="fas fa-check-circle"></i></span>
												<!-- <span class="bs-stepper-label">Complete Installation</span> -->
											</button>
										</div>
									</div>
									<form method="post" action="?action=success">
									<div class="bs-stepper-content">
										<!-- your steps content here -->
										<?php if ($action == 'server') : ?>
											<div id="server" class="content active" role="tabpanel" aria-labelledby="server-trigger">
												<div class="table-responsive">
													<table class="table">
														<?= $check = table($requiredServerExtensions) ?>
													</table>
												</div>
												
												<?php if($check = 0): ?>
													<a href="?action=file" class="btn btn-primary">Next</a>
												<?php else: ?>
													<a class="btn btn-danger" href="?action=server">ReCheck <i class="fas fa-sync-alt"></i></a>
												<?php endif ?>
											</div>
										<?php elseif ($action == 'file') : ?>
											<div id="file" class="content active" role="tabpanel" aria-labelledby="file-trigger">
												<h3>File Permissions</h3>
												<div class="table-responsive">
													<table class="table">
														<?= $error = folderPermissions($folderPermissions) ?>
													</table>
												</div>
												<a class="btn btn-primary"  href="?action=server">Previous</a>
												<?php if($error = 0): ?>
													<a href="?action=database" class="btn btn-primary">Next</a>
												<?php else: ?>
													<a href="?action=file" class="btn btn-danger"><i class="fas fa-sync-alt"></i> ReCheck </a>
												<?php endif ?>
											</div>
										<?php elseif ($action == 'database') : ?>
											<div id="database" class="content active" role="tabpanel" aria-labelledby="database-trigger">
												<h3>Connection Configuration</h3>
												<div class="row">
													<div class="col-12">
														<div class="form-group">
															<label>Website URL</label>
															<input name="url" class="form-control" value="<?php echo getWebURL(); ?>" type="text" required>
														</div>
													</div>
													<div class="h3 border-bottom col-12 mb-3">Purchase Verification</div>
													<div class="col-6">
														<div class="form-group">
															<label>Purchase E-mail</label>
															<input type="text" name="user" class="form-control" placeholder="Enter Anything" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Purchase Code</label>
															<input type="text" name="code" class="form-control" placeholder="Envato Purchase Code" required>
														</div>
													</div>
													<div class="h3 border-bottom col-12 mb-3">Database Configuration</div>
													<div class="col-6">
														<div class="form-group">
															<label>Database Name</label>
															<input type="text" name="db_name" class="form-control" placeholder="Database Name" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Database Host</label>
															<input type="text" name="db_host" class="form-control" placeholder="Database Host" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Database Password</label>
															<input type="text" name="db_pass" class="form-control" placeholder="Database Password">
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Database User</label>
															<input type="text" name="db_user" class="form-control" placeholder="Database User" required>
														</div>
													</div>
													<div class="h3 border-bottom col-12 mb-3">Admin Credential</div>
													<div class="col-6">
														<div class="form-group">
															<label>Username</label>
															<input type="text" name="username" value="admin" class="form-control" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Password</label>
															<input type="text" name="password" value="admin" class="form-control" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Confirm Password</label>
															<input type="text" name="passconf" value="admin" class="form-control" required>
														</div>
													</div>
													<div class="col-6">
														<div class="form-group">
															<label>Email Address</label>
															<input name="email" placeholder="Your Email address" class="form-control" type="email" required>
														</div>
													</div>
												</div>
												<a href="?action=file" class="btn btn-danger">Previous</a>
												<button type="submit" class="btn btn-primary">Submit</a>
											</div>
										<?php elseif ($action == 'success') : ?>
											<div id="success" class="content active text-center pb-3" role="tabpanel" aria-labelledby="success-trigger">
												<h3>Installation Complete</h3>
												<?php if ($_POST && $process = installNow() == true): ?>
												<div class="icon icon--order-success svg mb-4">
													<svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
														<g fill="none" stroke="#8EC343" stroke-width="2">
															<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
															<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
														</g>
													</svg>
													<div class="p-3"><?= $process ?></div>
													<?php echo "<pre>"; var_dump($_POST) ?>
												</div>
												<a class="btn btn-primary" href="/">Website</a>
												<a type="submit" class="btn btn-primary" href="/admin">Admin Panel</a>
												<?php endif ?>
											</div>
										<?php endif ?>
									</div>
								</div>
								</form>
							<?php else : ?>
								<div class="tab">
									<div class="title">
										<h1>TERMS OF USE</h1>
									</div>
									<div class="card card-body">
										<div class="item">
											<h4 class="subtitle">License to be used on one(1) domain(website) only!</h4>
											<p> The Regular license is for one website or domain only. If you want to use it on multiple websites or domains you have to purchase more licenses (1 website = 1 license). The Regular License grants you an ongoing, non-exclusive, worldwide license to make use of the item.</p>
										</div>
										<div class="item">
											<h5 class="subtitle font-weight-bold">You Can:</h5>
											<ul class="check-list">
												<li> Use on one(1) domain only. </li>
												<li> Modify or edit as you want. </li>
												<li> Translate to your choice of language(s).</li>
											</ul>
											<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> If any issue or error occurred for your modification on our code/database, we will not be responsible for that. </span>
										</div>
										<div class="item">
											<h5 class="subtitle font-weight-bold">You Cannot: </h5>
											<ul class="check-list">
												<li class="no"> Resell, distribute, give away, or trade by any means to any third party or individual. </li>
												<li class="no"> Include this product into other products sold on any market or affiliate websites. </li>
												<li class="no"> Use on more than one(1) domain. </li>
											</ul>
										</div>
										<div class="item">
											<p class="info">For more information, Please Check <a href="https://codecanyon.net/licenses/faq" target="_blank">The License FAQ</a></p>
										</div>
										<div class="item text-right">
											<a href="?action=server" class="btn btn-primary theme-button choto">I Agree, Next Step</a>
										</div>
									</div>
								</div>
							<?php endif ?>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						Visit <a href="https://github.com/Johann-S/bs-stepper/#how-to-use-it">bs-stepper documentation</a> for more examples and information about the plugin.
					</div>
				</div>
				<!-- /.card -->
			</div>
		</div>
		<!-- /.row -->
	</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
	<script>
		// BS-Stepper Init
		document.addEventListener('DOMContentLoaded', function() {
			window.stepper = new Stepper(document.querySelector('.bs-stepper'))
		})
	</script>
</body>

</html>