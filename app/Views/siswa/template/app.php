<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<?= $this->include('siswa/template/partials/_styles') ?>
	<?= $this->renderSection('meta_tags') ?>
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
	<meta name="csrf-token" content="<?= csrf_hash() ?>">
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<div id="global-loader">

		<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>

		<div class="mt-5">
			<div class="text-gray-600 fw-semibold fs-6 text-center">Sedang memuat...</div>
		</div>
	</div>
	<!--begin::Theme mode setup on page load-->
	<script>
		var defaultThemeMode = "light";
		var themeMode;
		if (document.documentElement) {
			if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
				themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
			} else {
				if (localStorage.getItem("data-bs-theme") !== null) {
					themeMode = localStorage.getItem("data-bs-theme");
				} else {
					themeMode = defaultThemeMode;
				}
			}
			if (themeMode === "system") {
				themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
			}
			document.documentElement.setAttribute("data-bs-theme", themeMode);
		}
	</script>
	<!--end::Theme mode setup on page load-->
	<!--begin::App-->
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<!--begin::Page-->
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<!--begin::Header-->
			<?= $this->include('siswa/template/partials/_header') ?>
			<!--end::Header-->
			<!--begin::Wrapper-->
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				<!--begin::Toolbar-->
				<?php if(session()->get('id')): ?>
					<?= $this->include('siswa/template/partials/_toolbar') ?>
				<?php endif; ?>
				<!--end::Toolbar-->
				<!--begin::Wrapper container-->
				<div class="app-container container-xxl">
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<?= $this->renderSection('content') ?>
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?= $this->include('siswa/template/partials/_footer') ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper container-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::App-->

	
	<!--end::Custom Javascript-->
	<?= $this->include('siswa/template/partials/_scripts') ?>
	<?= $this->renderSection('scripts') ?>
	<!--end::Javascript-->
	<?= $this->include('siswa/template/partials/_script_notif') ?>
	<?= $this->include('siswa/template/partials/_script_lazy') ?>
	<?= $this->include('siswa/template/partials/_script_alert') ?>
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
<!--end::Body-->

</html>