<?php
// Cek sesi user & status chat
$chatUserId = session()->get('id');
$chatUserRole = session()->get('role');
$isChatAdmin = ($chatUserRole == 1 || $chatUserRole === 'admin');
$isChatGuru = ($chatUserRole == 3 || $chatUserRole === 'guru');
$isChatEnabled = in_array(strtolower((string)setting('chat_status', 'true')), ['true', '1', 'yes', 'on']);
$isGuruChatEnabled = in_array(strtolower((string)setting('chat_guru_status', 'false')), ['true', '1', 'yes', 'on']);
$uriPath = service('request')->getUri()->getPath();
$currentUriStr = uri_string();
$isChatPage = (strpos($uriPath, 'chat') !== false || strpos($currentUriStr, 'chat') !== false);
$isUjianPgPage = (strpos($uriPath, 'sw-siswa/ujian/lihat-pg') !== false || strpos($currentUriStr, 'sw-siswa/ujian/lihat-pg') !== false);
$canShowChatWidget = $isChatAdmin || $isChatEnabled;
?>

<?php if ($chatUserId && $canShowChatWidget && !$isChatPage && !$isUjianPgPage): ?>
<style>
    .kb-chat-floating-btn {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 1040;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #29459A 0%, #1e3a8a 100%);
        color: #ffffff;
        border: none;
        box-shadow: 0 10px 25px rgba(41, 69, 154, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }
    .kb-chat-floating-btn:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 14px 30px rgba(41, 69, 154, 0.5);
        color: #ffffff;
    }
    .kb-chat-badge-unread {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #F1416C;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        border-radius: 50rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        border: 2px solid #ffffff;
        animation: pulseBadge 1.8s infinite;
    }
    @keyframes pulseBadge {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    @media (max-width: 576px) {
        .kb-chat-floating-btn {
            right: 14px;
            bottom: 14px;
            width: 50px;
            height: 50px;
        }
    }
</style>

<a href="<?= base_url('chat') ?>" class="kb-chat-floating-btn" id="kb_chat_floating_btn" title="Buka Diskusi & Chat Realtime">
    <i class="ki-duotone ki-messages fs-1 text-white">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
    </i>
    <span class="kb-chat-badge-unread d-none" id="kb_chat_floating_badge">0</span>
</a>

<script>
    (function() {
        let lastUnreadCount = null;
        let audioCtx = null;

        function playIncomingRingtone() {
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) return;
                if (!audioCtx) audioCtx = new AudioContextClass();
                if (audioCtx.state === 'suspended') audioCtx.resume();

                const now = audioCtx.currentTime;
                // Nada 1 (D5 -> 587.33 Hz)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                gain1.gain.setValueAtTime(0.25, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.18);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start(now);
                osc1.stop(now + 0.18);

                // Nada 2 (A5 -> 880 Hz)
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, now + 0.13);
                gain2.gain.setValueAtTime(0.3, now + 0.13);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.start(now + 0.13);
                osc2.stop(now + 0.45);
            } catch (e) {}
        }

        function checkUnreadChat() {
            fetch("<?= base_url('chat/status') ?>", { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    const btn = document.getElementById('kb_chat_floating_btn');
                    const badge = document.getElementById('kb_chat_floating_badge');
                    if (!btn || !badge) return;

                    // Jika chat dinonaktifkan dan bukan admin, sembunyikan tombol
                    if (!data.enabled) {
                        btn.style.display = 'none';
                        return;
                    } else {
                        btn.style.display = 'flex';
                    }

                    const currentUnread = Number(data.unreadTotal || 0);
                    if (lastUnreadCount !== null && currentUnread > lastUnreadCount) {
                        playIncomingRingtone();
                    }
                    lastUnreadCount = currentUnread;

                    if (currentUnread > 0) {
                        badge.innerText = currentUnread > 99 ? '99+' : currentUnread;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                })
                .catch(() => {});
        }

        // Jalankan pengecekan unread saat pertama kali load dan setiap 5 detik
        document.addEventListener('DOMContentLoaded', checkUnreadChat);
        setInterval(checkUnreadChat, 5000);
    })();
</script>
<?php endif; ?>
