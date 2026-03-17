<style>
     #notifScroll {
         height: 400px;
         overflow-y: scroll;
         -ms-overflow-style: none;
         /* IE dan Edge */
         scrollbar-width: none;
         /* Firefox */
     }

     #notifScroll::-webkit-scrollbar {
         display: none;
     }

     :root {
         --carousel-height: 350px;
         --slide-width: calc(100% / 3);
     }

     * {
         box-sizing: border-box;
     }

     .fs-1 {
         font-size: .875rem
     }

     /* Navbar dengan background gambar */
    .navbar {
         background: url('<?= $image_nav ?>') center/cover no-repeat !important;
         height: 56px;
     }

    .header-container .navbar-nav.theme-brand{
        background-color: transparent !important;
        background-image: none !important;
        border-right:none !important;
    }


     .btn-primary {
         background-color: var(--bs-primary) !important;
         /*background-color:none !important;*/
     }

     .bg-primary {
         background-color: var(--bs-primary) !important;
     }

     .table>thead>tr>th {
         color: var(--bs-primary) !important;
     }

     div.dataTables_wrapper div.dataTables_info {
         color: var(--bs-primary) !important;
     }

     .page-item.active .page-link {
         background-color: var(--bs-primary) !important;
     }

     .user-profile .widget-content-area .user-info p {
         color: var(--bs-primary) !important;
     }

     .user-profile .widget-content-area .edit-profile {
         background-color: var(--bs-primary) !important;
         background: var(--bs-primary) !important;
     }

     .infobox-3 .info-icon {
         background: var(--bs-primary) !important;
     }

     .widget-five .w-content div .task-left {
         color: var(--bs-primary) !important;
     }

     #sidebar ul.menu-categories li.menu:not(.active)>.dropdown-toggle[aria-expanded="true"] {
         background: var(--bs-primary) !important;
     }

     #sidebar ul.menu-categories ul.submenu>li.active a {
         color: var(--bs-primary) !important;
     }

     #sidebar ul.menu-categories li.menu.active>.dropdown-toggle {
         background: var(--bs-primary) !important;
     }


     .whatsapp-button {
         position: fixed;
         width: 70px;
         height: 70px;
         bottom: 20px;
         right: 20px;
         background-color: #25D366;
         color: #fff;
         border-radius: 100%;
         text-align: center;
         font-size: 30px;
         z-index: 100;
         transition: all 0.3s ease;
         animation: zoomGlow 2s ease-in-out infinite;
     }

     @keyframes zoomGlow {
         0% {
             transform: scale(1);
             box-shadow: 0 0 10px rgba(37, 211, 102, 0.5);
         }

         50% {
             transform: scale(1.2);
             box-shadow: 0 0 25px rgba(37, 211, 102, 0.9);
         }

         100% {
             transform: scale(1);
             box-shadow: 0 0 10px rgba(37, 211, 102, 0.5);
         }
     }

     /* Chat box */
     .chat-box {
         position: fixed;
         bottom: 70px;
         right: 20px;
         width: 320px;
         max-height: 500px;
         background: white;
         border-radius: 10px;
         box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
         display: none;
         flex-direction: column;
         overflow: hidden;
         z-index: 1050;
     }

     .chat-header {
         background: #6f42c1;
         color: white;
         padding: 10px;
         display: flex;
         justify-content: space-between;
         align-items: center;
     }

     .chat-body {
         padding: 15px;
         flex: 1;
         overflow-y: auto;
         font-size: 14px;
     }

     .user-info img {
         width: 90px;
         aspect-ratio: 1 / 1;
         /* memastikan gambar tetap persegi */
         border-radius: 50%;
         /* membuat bulat */
         object-fit: cover;
         /* potong gambar biar proporsional */
         object-position: center;
         /* pusatkan wajah */
         border: 4px solid #fff;
         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
         background: #fff;
         display: block;
         margin: 0 auto;
     }
 
     /* Indikator Belum Dibaca */
     .bg-light-success {
         background-color: #f0fff4 !important;
         /* Hijau sangat muda */
         border-left: 3px solid #28a745;
     }

     .notification-item {
         transition: background 0.2s;
         border-bottom: 1px solid #f1f1f1;
     }

     .notification-item:hover {
         background-color: #f8f9fa;
         text-decoration: none !important;
     }

     .fs-7 {
         font-size: 0.85rem !important;
     }

     .fs-8 {
         font-size: 0.75rem !important;
     }

     .scroll-y::-webkit-scrollbar {
         width: 5px;
     }

     .scroll-y::-webkit-scrollbar-thumb {
         background: #e2e2e2;
         border-radius: 10px;
     }

     /* Item Notifikasi Dasar */
     .notif-item {
         border-bottom: 1px solid #f1f1f1;
         padding: 12px 15px;
         transition: all 0.2s ease;
         /* Transisi halus */
         cursor: pointer;
         background-color: #ffffff;
     }

     /* Efek HOVER: Warna latar berubah saat kursor di atasnya */
     .notif-item:hover {
         background-color: #f8f9fc !important;
         /* Warna abu-abu sangat muda */
         box-shadow: inset 4px 0 0 0 #4e73df;
         /* Garis vertikal biru di kiri saat hover */
         text-decoration: none !important;
     }

     /* Jika belum dibaca (bg-light-success) tetap pertahankan warna hijaunya sedikit */
     .notif-item.bg-light-success {
         background-color: #f4fbf6;
     }

     /* Hilangkan garis bawah pada link di dalam notif */
     .notif-item a.btn-mark-read:hover {
         text-decoration: none;
         color: #4e73df !important;
     }

     /* Memastikan teks tidak overflow */
     .fs-7 {
         font-size: 0.85rem;
     }

     .fs-8 {
         font-size: 0.75rem;
     }
 </style>



 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

 <style>
     .custom-tooltip {
         --bs-tooltip-bg: 'red';
         --bs-tooltip-color: 'white';
     }

     .notif-dropdown {
         width: 320px;
         max-height: 400px;
         overflow-y: auto;
     }

     .notif-item {
         padding: 10px 14px;
         white-space: normal;
     }

     .notif-text {
         font-size: 13px;
         color: #444;
         overflow: hidden;
         display: -webkit-box;
         -webkit-line-clamp: 2;
         /* maksimal 2 baris */
         -webkit-box-orient: vertical;
     }

     .notif-time {
         font-size: 11px;
         color: #999;
         margin-top: 2px;
     }

     @media (max-width: 576px) {
         .notif-dropdown {
             width: 95vw;
         }
     }



    /* Memperkecil input pencarian */
    .dataTables_filter input {
        width: 200px !important; /* Atur lebar sesuai keinginan */
        height: 30px !important;  /* Agar lebih ramping */
        display: inline-block;
        margin-left: 10px;
        border-radius: 5px;
    }

    /* Memperkecil dropdown 'Show Entries' (Filter jumlah data) */
    .dataTables_length select {
        width: 70px !important;
        height: 30px !important;
        padding: 5px !important;
        border-radius: 5px;
    }

    /* Merapikan posisi label */
    .dataTables_wrapper .dataTables_filter label, 
    .dataTables_wrapper .dataTables_length label {
        font-size: 13px;
        font-weight: 600;
        color: #515365;
    }

    .img-profile-detail {
        width: 140px !important;   /* Lebar tetap */
        height: 140px !important;  /* Tinggi tetap (sama agar persegi) */
        object-fit: cover;         /* Rahasia agar foto tetap proporsional */
        object-position: top;      /* Fokus pada area kepala/atas (sangat penting untuk pas foto) */
        border-radius: 12px;       /* Membuat sudut melengkung halus */
        border: 3px solid #fff;    /* Frame putih agar elegan */
        box-shadow: 0 4px 10px rgba(0,0,0,0.15); /* Bayangan halus */
    }

    .select2-container--default .select2-selection--single {
        height: 45px !important;
        /* Sesuaikan dengan tinggi input Bootstrap Anda */
        padding: 8px;
        border: 1px solid #ced4da;
    }
</style>