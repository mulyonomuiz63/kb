<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="card-header pt-7" id="kt_chat_contacts_header">
                            <form class="w-100 position-relative" autocomplete="off">
                                <i class="ki-outline ki-magnifier fs-2 position-absolute top-50 ms-5 translate-middle-y"></i>
                                <input type="text" class="form-control form-control-solid px-13" name="search" value="" placeholder="Cari nama atau pesan..." />
                            </form>
                        </div>

                        <div class="card-body pt-5" id="kt_chat_contacts_body">
                            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px">

                                <div class="d-flex flex-stack py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px symbol-circle">
                                            <span class="symbol-label bg-light-danger text-danger fs-6 fw-bolder">B</span>
                                            <div class="bg-success position-absolute border border-4 border-body h-9px w-9px ms-10 mt-10 start-0 top-0"></div>
                                        </div>
                                        <div class="ms-5">
                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Budi Santoso</a>
                                            <div class="fw-semibold fs-7 text-muted text-truncate w-150px">Ingin tanya jadwal ujian...</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end ms-2">
                                        <span class="text-muted fs-8 mb-1">5 Menit</span>
                                        <span class="badge badge-sm badge-circle badge-light-danger">2</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed d-none"></div>
                                <div class="d-flex flex-stack py-4 bg-light-primary px-3 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px symbol-circle">
                                            <img alt="Pic" src="https://ui-avatars.com/api/?name=Ani+Lestari&background=random" />
                                        </div>
                                        <div class="ms-5">
                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Ani Lestari</a>
                                            <div class="fw-semibold fs-7 text-muted">Sedang mengetik...</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end ms-2">
                                        <span class="text-muted fs-8 mb-1">Baru saja</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_chat_messenger">
                        <div class="card-header" id="kt_chat_messenger_header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Ani Lestari</a>
                                    <div class="mb-0 lh-1">
                                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                        <span class="fs-7 fw-semibold text-muted">Online</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" id="kt_chat_messenger_body">
                            <div class="scroll-y me-n5 pe-5 h-450px h-lg-auto pb-10"
                                data-kt-scroll="true"
                                data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer"
                                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_messenger_body"
                                data-kt-scroll-offset="5px">

                                <div class="d-flex justify-content-start mb-10">
                                    <div class="d-flex flex-column align-items-start">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="https://ui-avatars.com/api/?name=Ani+Lestari" />
                                            </div>
                                            <div class="ms-3">
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Ani</a>
                                                <span class="text-muted fs-7 mb-1">2 Menit yang lalu</span>
                                            </div>
                                        </div>
                                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start">
                                            Halo Admin, apakah modul brevet A sudah bisa diunduh?
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mb-10">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-3">
                                                <span class="text-muted fs-7 mb-1">Baru saja</span>
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">Anda</a>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="https://ui-avatars.com/api/?name=CS" />
                                            </div>
                                        </div>
                                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end">
                                            Halo Ani, sudah bisa ya. Silakan cek di menu Materi Belajar.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer pt-6" id="kt_chat_messenger_footer">
                            <textarea class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" placeholder="Tulis pesan..."></textarea>

                            <div class="d-flex flex-stack">
                                <div class="d-flex align-items-center me-2">
                                    <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button" data-bs-toggle="tooltip" title="Kirim Gambar">
                                        <i class="ki-outline ki-paper-clip fs-3"></i>
                                    </button>
                                </div>
                                <button class="btn btn-primary" type="button" data-kt-element="send">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>