<?php if (session()->get('id') && strtolower((string) setting('asisten_status', 'false')) === 'true'): ?>
<style>
    .kb-asisten-launch,
    .kb-asisten-panel,
    .kb-asisten-panel * {
        box-sizing: border-box;
    }

    .kb-asisten-launch {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 1040;
        width: 60px;
        height: 60px;
        border: 0;
        border-radius: 50%;
        background: linear-gradient(160deg, #3558c4 0%, #1d4695 100%);
        color: #fff;
        box-shadow: 0 12px 28px rgba(29, 70, 149, 0.35);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: kb-asisten-in 0.45s ease both;
    }

    .kb-asisten-launch:hover {
        transform: translateY(-2px);
    }

    .kb-asisten-launch .kb-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #50cd89;
        border: 2px solid #fff;
    }

    .kb-asisten-panel {
        position: fixed;
        right: 22px;
        bottom: 96px;
        z-index: 1040;
        width: min(380px, calc(100vw - 24px));
        height: min(560px, calc(100vh - 130px));
        background: #fff;
        border: 1px solid #e6ebf4;
        border-radius: 22px;
        box-shadow: 0 24px 60px rgba(22, 39, 84, 0.18);
        display: none;
        flex-direction: column;
        overflow: hidden;
        font-family: inherit;
    }

    .kb-asisten-panel.is-open {
        display: flex;
        animation: kb-asisten-up 0.22s ease;
    }

    .kb-asisten-head {
        background: linear-gradient(155deg, #162754 0%, #1d4695 70%);
        color: #fff;
        padding: 16px 16px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .kb-asisten-head strong {
        display: block;
        font-size: 15px;
        font-weight: 700;
    }

    .kb-asisten-head span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.78);
    }

    .kb-asisten-close {
        margin-left: auto;
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }

    .kb-asisten-log {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f6f8fc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .kb-asisten-msg {
        max-width: 86%;
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .kb-asisten-msg.bot {
        align-self: flex-start;
        background: #fff;
        color: #1b2437;
        border: 1px solid #e6ebf4;
    }

    .kb-asisten-msg.me {
        align-self: flex-end;
        background: #1d4695;
        color: #fff;
    }

    .kb-asisten-form {
        display: flex;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid #e6ebf4;
        background: #fff;
    }

    .kb-asisten-form textarea {
        flex: 1;
        resize: none;
        height: 46px;
        max-height: 96px;
        border: 1px solid #e4e7ec;
        border-radius: 12px;
        padding: 12px;
        font-size: 14px;
        font-family: inherit;
        outline: none;
    }

    .kb-asisten-form textarea:focus {
        border-color: #1d4695;
        box-shadow: 0 0 0 3px rgba(29, 70, 149, 0.12);
    }

    .kb-asisten-form button {
        width: 46px;
        height: 46px;
        border: 0;
        border-radius: 12px;
        background: #1d4695;
        color: #fff;
        cursor: pointer;
        flex: 0 0 auto;
    }

    .kb-asisten-form button:disabled {
        opacity: 0.55;
        cursor: wait;
    }

    @keyframes kb-asisten-in {
        from { opacity: 0; transform: translateY(10px) scale(0.92); }
        to { opacity: 1; transform: none; }
    }

    @keyframes kb-asisten-up {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: none; }
    }

    @media (max-width: 576px) {
        .kb-asisten-launch {
            right: 14px;
            bottom: 14px;
        }

        .kb-asisten-panel {
            right: 12px;
            bottom: 84px;
        }
    }
</style>

<button type="button" class="kb-asisten-launch" id="kbAsistenLaunch" aria-label="Buka asisten Kelas Brevet">
    <span class="kb-dot"></span>
    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</button>

<section class="kb-asisten-panel" id="kbAsistenPanel" aria-label="Asisten Kelas Brevet">
    <div class="kb-asisten-head">
        <div>
            <strong>Asisten Kelas Brevet</strong>
            <span>Tanya cara pakai halaman peserta</span>
        </div>
        <button type="button" class="kb-asisten-close" id="kbAsistenClose" aria-label="Tutup">×</button>
    </div>
    <div class="kb-asisten-log" id="kbAsistenLog"></div>
    <form class="kb-asisten-form" id="kbAsistenForm">
        <textarea id="kbAsistenInput" maxlength="1500" placeholder="Contoh: cara unduh sertifikat" required></textarea>
        <button type="submit" id="kbAsistenSend" aria-label="Kirim">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </form>
</section>

<script>
(function () {
    var launch = document.getElementById('kbAsistenLaunch');
    var panel = document.getElementById('kbAsistenPanel');
    var closeBtn = document.getElementById('kbAsistenClose');
    var log = document.getElementById('kbAsistenLog');
    var form = document.getElementById('kbAsistenForm');
    var input = document.getElementById('kbAsistenInput');
    var send = document.getElementById('kbAsistenSend');
    var history = [];
    var greeted = false;

    function csrf() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function setCsrf(token) {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && token) meta.setAttribute('content', token);
    }

    function addMessage(role, text) {
        var bubble = document.createElement('div');
        bubble.className = 'kb-asisten-msg ' + (role === 'user' ? 'me' : 'bot');
        bubble.textContent = text;
        log.appendChild(bubble);
        log.scrollTop = log.scrollHeight;
    }

    function openPanel() {
        panel.classList.add('is-open');
        if (!greeted) {
            greeted = true;
            addMessage('assistant', 'Halo. Saya asisten Kelas Brevet. Tanya saja cara memakai materi, ujian, sertifikat, webinar, histori, affiliate, IKH, atau akun Anda.');
        }
        input.focus();
    }

    launch.addEventListener('click', function () {
        if (panel.classList.contains('is-open')) {
            panel.classList.remove('is-open');
        } else {
            openPanel();
        }
    });
    closeBtn.addEventListener('click', function () {
        panel.classList.remove('is-open');
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        var text = input.value.trim();
        if (!text || send.disabled) return;

        addMessage('user', text);
        input.value = '';
        send.disabled = true;

        var body = new FormData();
        body.append('csrf_test_name', csrf());
        body.append('message', text);
        body.append('history', JSON.stringify(history));

        fetch('<?= base_url('sw-siswa/asisten/chat') ?>', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), 'X-Requested-With': 'XMLHttpRequest' },
            body: body
        }).then(function (res) {
            return res.json().then(function (data) {
                return { ok: res.ok, data: data };
            });
        }).then(function (result) {
            var data = result.data || {};
            setCsrf(data.csrf);
            var reply = data.reply || 'Asisten belum bisa menjawab. Coba lagi sebentar.';
            addMessage('assistant', reply);
            if (result.ok && data.ok) {
                history.push({ role: 'user', content: text });
                history.push({ role: 'assistant', content: reply });
                if (history.length > 8) history = history.slice(-8);
            }
        }).catch(function () {
            addMessage('assistant', 'Koneksi terputus. Periksa internet Anda, lalu kirim ulang.');
        }).finally(function () {
            send.disabled = false;
            input.focus();
        });
    });
})();
</script>
<?php endif; ?>
