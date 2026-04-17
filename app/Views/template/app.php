<!DOCTYPE html>
<html lang="en">

<head>
	<?= $this->include('template/partials/header_css') ?>
	<?= $this->renderSection('styles') ?>
</head>

<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

				<?= $this->include('template/partials/navbar') ?>

				<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
					<div class="flex-row-fluid" id="kt_content">
						<?= $this->include('template/partials/toolbar') ?>
						<?= $this->renderSection('content') ?>
					</div>
				</div>

				<?= $this->include('template/partials/footer') ?>
			</div>
		</div>
	</div>
	<script src="https://topcs.id/widget.js" data-tenant="kelas-brevet" data-mode="bubble" data-position="right" data-color="#2563eb"></script>
	<?= $this->include('template/partials/footer_js') ?>
	<?= session()->getFlashdata('pesan'); ?>
	<?= $this->renderSection('scripts') ?>
	<?= $this->include('template/partials/notification') ?>
</body>

</html>