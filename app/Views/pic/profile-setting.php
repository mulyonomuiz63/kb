<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>

<?= $this->include('template/sidebar/pic'); ?>



<div id="content" class="main-content">

    <div class="layout-px-spacing">

        <div class="row layout-spacing">

            <!-- Content -->

            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">

                <div class="user-profile layout-spacing">

                    <div class="widget-content widget-content-area">

                        <div class="d-flex justify-content-between">

                            <h3 class="">My Profile</h3>

                            <a href="javascript:void(0);" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">

                                    <path d="M12 20h9"></path>

                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>

                                </svg></a>

                        </div>

                        <div class="text-center user-info">

                            <img src="<?= base_url('assets/Mitra-assets/user/') . '/' . $pic->avatar; ?>" class="img-user" alt="avatar" style="width: 125px; height: 125px;">

                            <p class=""><?= $pic->nama_pic; ?></p>

                        </div>

                        <div class="user-info-list" style="margin-top: -20px;">

                            <div class="text-center">

                                <ul class="contacts-block list-unstyled">

                                    <li class="contacts-block__item">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">

                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>

                                            <polyline points="22,6 12,13 2,6"></polyline>

                                        </svg><br><?= $pic->email; ?>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>
            </div>



            <div class="col-xl-8 col-lg-6 col-md-7 col-sm-12 layout-top-spacing">



                <div class="skills layout-spacing ">

                    <div class="widget-content widget-content-area">

                        <h3 class="">Update Profile</h3>

                        <form action="<?= base_url('Pic/edit_profile'); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                            <div class="form-group">

                                <label for="">Nama</label>

                                <input type="text" name="nama_pic" id="nama_pic" value="<?= $pic->nama_pic; ?>" class="form-control" required>

                            </div>

                            <div class="form-group">

                                <label for="">Email</label>

                                <input type="email" name="email" id="email" value="<?= $pic->email; ?>" class="form-control" required readonly>

                            </div>

                            <div class="form-group">

                                <label for="">Foto</label>

                                <div class="custom-file">

                                    <input type="file" class="custom-file-input" name="avatar" id="customFile" accept=".jpg, .png, .jpeg" onchange="previewImg()">

                                    <input type="hidden" name="gambar_lama" value="<?= $pic->avatar; ?>">

                                    <label class="custom-file-label" for="customFile">Choose file</label>

                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save</button>

                        </form>

                    </div>

                </div>



                <div class="skills layout-spacing">

                    <div class="widget-content widget-content-area">

                        <h3 class="">Password</h3>

                        <form action="<?= base_url('Pic/edit_password'); ?>" method="post">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                            <div class="form-group">

                                <label for="">Current Password</label>

                                <input type="password" name="current_password" class="form-control" required>

                            </div>

                            <div class="form-group">

                                <label for="">New Password</label>

                                <input type="password" name="password" class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>

                        </form>

                    </div>

                </div>



            </div>



        </div>

    </div>

    <div class="footer-wrMitraer">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>

</div>

<!--  END CONTENT AREA  -->

<script>
    <?= session()->getFlashdata('pesan'); ?>



    function previewImg() {

        const gambar = document.querySelector('#customFile');

        const gambarLable = document.querySelector('.custom-file-label');

        const imgPreview = document.querySelector('.img-user');



        gambarLable.textContent = gambar.files[0].name;



        const filegambar = new FileReader();

        filegambar.readAsDataURL(gambar.files[0]);



        filegambar.onload = function(e) {

            imgPreview.src = e.target.result;

        }

    }
    
    function change() {

      // membuat variabel berisi tipe input dari id='pass', id='pass' adalah form input password 
      var x = document.getElementById('pass').type;

      //membuat if kondisi, jika tipe x adalah password maka jalankan perintah di bawahnya
      if (x == 'password') {

        //ubah form input password menjadi text
        document.getElementById('pass').type = 'text';

        //ubah icon mata terbuka menjadi tertutup
        document.getElementById('mybutton').innerHTML = `<i class="bi bi-eye-slash-fill"></i>`;
      }
      else {

        //ubah form input password menjadi text
        document.getElementById('pass').type = 'password';

        //ubah icon mata terbuka menjadi tertutup
        document.getElementById('mybutton').innerHTML = `<i class="bi bi-eye-fill"></i>`;
      }
    }
</script>



<?= $this->endSection(); ?>