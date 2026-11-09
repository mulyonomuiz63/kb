<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body.form {
        margin: 0;
        min-height: 100vh;
        background: #eef2f8 !important;
        font-family: "Plus Jakarta Sans", Quicksand, sans-serif !important;
    }

    body.form > .form-container {
        display: block !important;
        min-height: 100vh;
        background: transparent !important;
    }

    body.form .form-form,
    body.form .form-form-wrap,
    body.form .form-form .form-container {
        width: 100% !important;
        max-width: none !important;
        min-height: 100vh !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        border: 0 !important;
        display: block !important;
        position: relative !important;
    }

    body.form .form-image {
        display: none !important;
    }

    .kb-login {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(320px, 1.05fr) minmax(360px, 0.95fr);
    }

    .kb-panel {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.16), transparent 28%),
            radial-gradient(circle at 85% 80%, rgba(120, 168, 255, 0.35), transparent 32%),
            linear-gradient(155deg, #162754 0%, #29459a 52%, #3b67d6 100%);
        padding: 56px 64px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .kb-panel::before,
    .kb-panel::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.14);
        pointer-events: none;
    }

    .kb-panel::before {
        width: 420px;
        height: 420px;
        right: -120px;
        top: -90px;
    }

    .kb-panel::after {
        width: 280px;
        height: 280px;
        left: -80px;
        bottom: -70px;
    }

    .kb-panel-top,
    .kb-panel-copy,
    .kb-points {
        position: relative;
        z-index: 1;
    }

    .kb-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .kb-panel h1 {
        margin: 22px 0 14px;
        max-width: 460px;
        font-size: 42px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #fff;
    }

    .kb-panel p.lead {
        max-width: 420px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 16px;
        line-height: 1.7;
    }

    .kb-points {
        display: grid;
        gap: 14px;
        margin-top: 36px;
    }

    .kb-point {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
    }

    .kb-point svg {
        flex: 0 0 auto;
        margin-top: 2px;
    }

    .kb-point strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
    }

    .kb-point span {
        display: block;
        margin-top: 2px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
    }

    .kb-panel-foot {
        position: relative;
        z-index: 1;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    .kb-stage {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 28px;
        background:
            radial-gradient(circle at top right, rgba(41, 69, 154, 0.08), transparent 36%),
            #f6f8fc;
    }

    .kb-card {
        width: 100%;
        max-width: 440px;
        background: #fff;
        border: 1px solid #e6ebf4;
        border-radius: 28px;
        box-shadow: 0 24px 60px rgba(22, 39, 84, 0.08);
        padding: 36px 36px 28px;
    }

    .kb-logo {
        display: flex;
        justify-content: center;
        margin-bottom: 22px;
    }

    .kb-logo img {
        width: auto;
        max-width: 210px;
        max-height: 72px;
        object-fit: contain;
    }

    .kb-card h2 {
        margin: 0;
        text-align: center;
        color: #162033;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .kb-card .kb-sub {
        margin: 8px 0 26px;
        text-align: center;
        color: #667085;
        font-size: 14.5px;
        line-height: 1.6;
    }

    .kb-field {
        margin-bottom: 16px;
    }

    .kb-field label {
        display: block;
        margin-bottom: 8px;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .kb-input {
        position: relative;
    }

    .kb-input svg.kb-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        pointer-events: none;
    }

    .kb-input input {
        width: 100%;
        height: 52px;
        border: 1px solid #e4e7ec !important;
        border-radius: 14px !important;
        background: #f8fafc !important;
        padding: 0 46px 0 46px !important;
        color: #101828 !important;
        font-size: 15px !important;
        font-family: inherit !important;
        box-shadow: none !important;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .kb-input input::placeholder {
        color: #98a2b3;
    }

    .kb-input input:focus {
        outline: none;
        background: #fff !important;
        border-color: #29459a !important;
        box-shadow: 0 0 0 4px rgba(41, 69, 154, 0.12) !important;
    }

    .kb-eye {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #667085;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .kb-eye:hover {
        background: #eef2f8;
        color: #29459a;
    }

    .kb-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 4px 0 18px;
        font-size: 13.5px;
    }

    .kb-row a {
        color: #29459a;
        font-weight: 700;
        text-decoration: none;
    }

    .kb-row a:hover {
        text-decoration: underline;
    }

    .kb-submit {
        width: 100%;
        height: 52px;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(180deg, #3558c4 0%, #29459a 100%);
        color: #fff;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.01em;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(41, 69, 154, 0.24);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }

    .kb-submit:hover {
        filter: brightness(1.05);
        transform: translateY(-1px);
        box-shadow: 0 14px 24px rgba(41, 69, 154, 0.28);
    }

    .kb-submit:active {
        transform: translateY(0);
    }

    .kb-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 20px 0 16px;
        color: #98a2b3;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .kb-divider::before,
    .kb-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e4e7ec;
    }

    .kb-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        height: 50px;
        border-radius: 14px;
        border: 1px solid #e4e7ec;
        background: #fff;
        color: #344054;
        font-size: 14.5px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .15s ease, border-color .15s ease;
    }

    .kb-google:hover {
        background: #f8fafc;
        border-color: #d0d5dd;
        color: #101828;
    }

    .kb-google img {
        width: 20px;
        height: 20px;
    }

    .kb-signup {
        margin: 22px 0 0;
        text-align: center;
        color: #667085;
        font-size: 14px;
    }

    .kb-signup a {
        color: #29459a;
        font-weight: 700;
        text-decoration: none;
    }

    .kb-signup a:hover {
        text-decoration: underline;
    }

    .kb-copy {
        margin: 18px 0 0;
        text-align: center;
        color: #98a2b3;
        font-size: 12px;
        line-height: 1.6;
    }

    .kb-copy a {
        color: #667085;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 980px) {
        .kb-login {
            grid-template-columns: 1fr;
        }

        .kb-panel {
            min-height: auto;
            padding: 32px 24px 28px;
        }

        .kb-panel h1 {
            font-size: 30px;
        }

        .kb-points,
        .kb-panel-foot {
            display: none;
        }

        .kb-stage {
            padding: 20px 16px 36px;
        }

        .kb-card {
            padding: 28px 20px 22px;
            border-radius: 22px;
        }
    }

    .kb-card-wide {
        max-width: 480px;
    }

    .kb-input select,
    .kb-input input[type="number"] {
        -moz-appearance: textfield;
        appearance: none;
    }

    .kb-input select {
        width: 100%;
        height: 52px;
        border: 1px solid #e4e7ec !important;
        border-radius: 14px !important;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2398a2b3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 14px center !important;
        padding: 0 40px 0 16px !important;
        color: #101828 !important;
        font-size: 15px !important;
        font-family: inherit !important;
        box-shadow: none !important;
    }

    .kb-input input[type="number"]::-webkit-outer-spin-button,
    .kb-input input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .kb-input input.kb-plain {
        padding: 0 16px !important;
    }

    .kb-wa {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .kb-wa .kb-input {
        flex: 1;
    }

    .kb-submit-side {
        width: auto;
        min-width: 112px;
        height: 52px;
        padding: 0 14px;
        flex: 0 0 auto;
        box-shadow: none;
    }

    .kb-submit:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .kb-otp {
        margin-top: 12px;
        padding: 14px;
        border: 1px dashed #c5d0ea;
        border-radius: 16px;
        background: #f5f8ff;
    }

    .kb-otp label {
        display: block;
        margin-bottom: 8px;
        color: #29459a;
        font-size: 13px;
        font-weight: 700;
    }

    .kb-note {
        margin-top: 8px;
        color: #667085;
        font-size: 12.5px;
    }

    .kb-timer {
        color: #d92d20;
        font-weight: 700;
    }

    .kb-badge {
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #ecfdf3;
        color: #067647;
        font-size: 13px;
        font-weight: 700;
    }
</style>
