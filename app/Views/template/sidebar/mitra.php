        <!--  BEGIN SIDEBAR  -->

        <div class="sidebar-wrapper sidebar-theme">



            <nav id="sidebar">

                <div class="profile-info">

                    <figure class="user-cover-image"></figure>

                    <div class="user-info">

                        <?= img_lazy('assets/Mitra-assets/user/'.session()->get('avatar'),"loading", ['class' => 'bg-white']) ?>

                        <h6 class=""><?= session()->get('nama') ?></h6>

                        <p class="">Mitra</p>

                    </div>

                </div>

                <div class="shadow-bottom"></div>

                <ul class="list-unstyled menu-categories" id="accordionExample">

                    <li class="menu <?= $dashboard['menu']; ?>">

                        <a href="<?= base_url('Mitra'); ?>" aria-expanded="<?= $dashboard['expanded']; ?>" class="dropdown-toggle">

                            <div class="">

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">

                                    <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>

                                    <polygon points="12 15 17 21 7 21 12 15"></polygon>

                                </svg>

                                <span>Dashboard</span>

                            </div>

                        </a>

                    </li>

                    <li class="menu menu-heading">

                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">

                                <line x1="5" y1="12" x2="19" y2="12"></line>

                            </svg><span>MASTER DATA</span></div>

                    </li>


                        

                    <li class="menu <?= $menu_profile['menu']; ?>">

                        <a href="<?= base_url('Mitra/profile'); ?>" aria-expanded="<?= $menu_profile['expanded']; ?>" class="dropdown-toggle">

                            <div class="">

                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">

                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>

                                    <circle cx="12" cy="7" r="4"></circle>

                                </svg>

                                <span>Profile</span>

                            </div>

                        </a>

                    </li>

                    <li class="menu">

                        <a href="<?= base_url('auth/logout'); ?>" aria-expanded="false" class="dropdown-toggle">

                            <div class="">

                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">

                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>

                                    <polyline points="16 17 21 12 16 7"></polyline>

                                    <line x1="21" y1="12" x2="9" y2="12"></line>

                                </svg>

                                <span>Logout</span>

                            </div>

                        </a>

                    </li>

                </ul>



            </nav>



        </div>

        <!--  END SIDEBAR  -->