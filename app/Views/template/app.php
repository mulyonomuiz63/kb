<!DOCTYPE html>
<html lang="en">

<head>
	<?= $this->include('template/partials/header_css') ?>
	<?= $this->renderSection('styles') ?>
	<style>
		/* Mencegah scroll saat loader aktif */
		body.page-loading {
			overflow: hidden !important;
		}

		#global-loader {
			transition: opacity 0.6s ease-out, visibility 0.6s;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: #ffffff;
			/* Warna putih solid agar konten tidak terlihat */
			z-index: 99999;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}

		/* Class untuk menghilangkan loader via JS */
		.loader-hidden {
			opacity: 0;
			visibility: hidden;
		}
	</style>
</head>

<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">
	<div id="global-loader">

		<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>

		<div class="mt-5">
			<div class="text-gray-600 fw-semibold fs-6 text-center">Sedang memuat...</div>
		</div>
	</div>
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
	<script>
		// Tambahkan class loading ke body saat mulai
		document.body.classList.add('page-loading');

		window.addEventListener('load', function() {
			const loader = document.getElementById('global-loader');
			if (loader) {
				// Beri sedikit jeda 300ms agar mata pengguna tidak kaget saat transisi
				setTimeout(() => {
					loader.classList.add('loader-hidden');
					document.body.classList.remove('page-loading');

					// Hapus elemen dari DOM setelah animasi selesai (opsional)
					setTimeout(() => {
						loader.remove();
					}, 600);
				}, 300);
			}
		});
	</script>
</body>

</html>