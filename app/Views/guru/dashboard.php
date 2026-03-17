<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --accent-color: #4361ee;
    }

    /* Profile Header Styling */
    .profile-header-banner {
        background: var(--primary-gradient);
        height: 150px;
        border-radius: 20px 20px 0 0;
        position: relative;
    }

    .profile-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-top: -75px;
        padding-bottom: 30px;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .profile-img-wrapper {
        width: 130px;
        height: 130px;
        background: white;
        border-radius: 50%;
        margin: 0 auto;
        padding: 5px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .profile-initial {
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 800;
        color: #764ba2;
    }

    /* Badge & Info Styling */
    .status-pill {
        background: #eef2ff;
        color: #4361ee;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 50px;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    /* List Content Styling */
    .content-box {
        background: #ffffff;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
    }

    .content-box:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.05);
    }

    .item-label {
        font-size: 0.75rem;
        color: #a0aec0;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .item-label i {
        font-size: 18px;
        margin-right: 8px;
        color: #4361ee;
    }

    .custom-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .custom-list-item {
        background: #f8fafc;
        margin-bottom: 8px;
        padding: 12px 15px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        color: #4a5568;
        border: 1px solid transparent;
    }

    .custom-list-item:hover {
        background: #fff;
        border-color: #e2e8f0;
        transform: scale(1.02);
    }

    .empty-state {
        color: #cbd5e0;
        font-style: italic;
        font-size: 0.9rem;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            
            <div class="profile-header-banner mt-4"></div>

            <div class="profile-container text-center">
                <div class="row text-left px-md-5 px-3 mt-4">
                    <div class="col-md-6 mb-4">
                        <div class="content-box">
                            <div class="item-label">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                Distribusi Kelas
                            </div>
                            <div class="custom-list">
                                <?php if (!empty($guru_kelas)) : ?>
                                    <?php foreach ($guru_kelas as $gk) : ?>
                                        <div class="custom-list-item">
                                            <span><?= $gk->nama_kelas; ?></span>
                                            <span class="badge badge-light-primary text-primary">Kelas</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="empty-state">Belum terdaftar di kelas manapun.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="content-box">
                            <div class="item-label">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                Mata Pelajaran
                            </div>
                            <div class="custom-list">
                                <?php if (!empty($guru_mapel)) : ?>
                                    <?php foreach ($guru_mapel as $gm) : ?>
                                        <div class="custom-list-item">
                                            <span><?= $gm->nama_mapel; ?></span>
                                            <i class="dot" style="width: 10px; height: 10px; background: #764ba2; border-radius: 50%;"></i>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="empty-state">Mata pelajaran belum ditentukan.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>