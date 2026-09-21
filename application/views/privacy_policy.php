
<style>
:root {
    --cream: #FBF8EE;
    --cream-dark: #FFF1BF;
    --ink: #17355C;
    --ink-soft: #456383;
    --ink-muted: #73849A;
    --accent: #235EA7;
    --accent-hover: #163F72;
    --gold-light: #FFF5D6;
    --border: rgba(23, 53, 92, 0.1);
    --border-strong: rgba(23, 53, 92, 0.18);
    --card-bg: #FFFFFF;
    --shadow-sm: 0 1px 4px rgba(23,53,92,0.06), 0 0 0 0.5px rgba(23,53,92,0.08);
    --shadow-md: 0 4px 20px rgba(23,53,92,0.09), 0 0 0 0.5px rgba(23,53,92,0.07);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --radius-xl: 24px;
    --font-display: 'Fraunces', Georgia, serif;
    --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
}
.vc-hero {
        background: linear-gradient(135deg, #fffdf5 0%, #fff3c8 100%);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        padding: 56px 40px 72px;
        position: relative;
        overflow: hidden;
    }

    .vc-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 120% at 110% 50%, rgba(35, 94, 167, .12) 0%, transparent 60%),
            radial-gradient(ellipse 50% 80% at -10% 80%, rgba(241, 193, 79, .18) 0%, transparent 55%);
        pointer-events: none;
    }

    .vc-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .vc-eyebrow {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vc-eyebrow::before {
        content: '';
        width: 20px;
        height: 1.5px;
        background: var(--accent);
        border-radius: 2px;
        display: inline-block;
        flex-shrink: 0;
    }

    .vc-hero h1 {
        font-family: var(--font-display);
        font-size: clamp(28px, 6vw, 52px);
        font-weight: 300;
        color: var(--ink);
        line-height: 1.1;
        letter-spacing: -.02em;
    }

    .vc-hero h1 em {
        font-style: italic;
        color: var(--accent);
    }

    .vc-hero-sub {
        font-size: 15px;
        color: var(--ink-soft);
        margin-top: 12px;
        max-width: 400px;
        line-height: 1.6;
    }

    .vc-hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--accent);
        color: #FAF8F3;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        padding: 13px 24px;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: background .18s, transform .15s;
        white-space: nowrap;
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
    }

    .vc-hero-cta:hover {
        background: var(--accent-hover);
        transform: translateY(-1px);
    }

    .vc-hero-cta svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

.policy-wrap {
    /* max-width: 820px; */
    margin: 0 auto;
    padding: 0 24px 80px;
}

/* TOC */
.toc-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-left: 4px solid var(--accent);
    border-radius: var(--radius-md);
    padding: 24px 28px;
    margin-bottom: 48px;
    box-shadow: var(--shadow-sm);
}
.toc-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--ink-muted);
    margin-bottom: 14px;
}
.toc-list {
    list-style: none;
    counter-reset: toc;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 24px;
}
.toc-list li { counter-increment: toc; }
.toc-list li a {
    font-size: 13.5px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: color 0.2s;
}
.toc-list li a::before {
    content: counter(toc, decimal-leading-zero);
    font-size: 11px;
    color: var(--ink-muted);
    font-weight: 600;
    min-width: 22px;
}
.toc-list li a:hover { color: var(--accent-hover); }

/* SECTIONS */
.policy-section {
    margin-bottom: 44px;
    scroll-margin-top: 90px;
}
.section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.section-num {
    width: 34px;
    height: 34px;
    border-radius: var(--radius-sm);
    background: var(--accent);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.section-title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 600;
    color: var(--ink);
}
.policy-section p {
    color: var(--ink-soft);
    font-size: 14.5px;
    line-height: 1.8;
    margin-bottom: 12px;
}
.policy-section p:last-child { margin-bottom: 0; }

/* LIST */
.policy-list {
    list-style: none;
    margin: 12px 0 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.policy-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14.5px;
    color: var(--ink-soft);
    line-height: 1.7;
}
.policy-list li::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--accent);
    margin-top: 8px;
    flex-shrink: 0;
}

/* HIGHLIGHT */
.highlight-box {
    background: var(--gold-light);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-md);
    padding: 16px 20px;
    margin: 16px 0;
    font-size: 14px;
    color: var(--ink);
    line-height: 1.7;
}

/* CONTACT CARD */
.contact-card {
    background: var(--card-bg);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-lg);
    padding: 32px;
    text-align: center;
    box-shadow: var(--shadow-md);
    margin-top: 48px;
}
.contact-card h3 {
    font-family: var(--font-display);
    font-size: 22px;
    color: var(--ink);
    margin-bottom: 8px;
}
.contact-card p {
    color: var(--ink-soft);
    font-size: 14px;
    margin-bottom: 24px;
}
.contact-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}
.contact-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.contact-btn-primary {
    background: var(--accent);
    color: #fff;
}
.contact-btn-primary:hover { background: var(--accent-hover); }
.contact-btn-secondary {
    background: var(--card-bg);
    color: var(--accent);
    border: 1px solid var(--border-strong);
}
.contact-btn-secondary:hover { border-color: var(--accent); }

/* META BADGE */
.page-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 36px;
    flex-wrap: wrap;
}
.page-meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--ink-muted);
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 5px 12px;
}

@media (max-width: 640px) {
    .toc-list { grid-template-columns: 1fr; }
    .contact-links { flex-direction: column; }
    .policy-wrap { padding: 0 16px 60px; }
}
</style>

<!-- HERO -->
<div class="vc-hero" style="margin-bottom: 48px;">
    <div class="vc-hero-inner">
        <div>
            <div class="vc-eyebrow">Surya Deep Car Rent</div>
            <h1>
                Privacy <br>
                <em>Policy</em>
            </h1>
            <p class="vc-hero-sub">
                We respect your privacy. Learn how we collect, use, and protect your personal information.
            </p>
        </div>
        <a class="vc-hero-cta" href="<?php echo base_url('bookings/create'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
            Book a Car
        </a>
    </div>
</div>

<!-- CONTENT -->
<div class="policy-wrap">

    <!-- META -->
    <div class="page-meta">
        <span class="page-meta-badge">📅 Effective: January 2025</span>
        <span class="page-meta-badge">📍 Ahmedabad, Gujarat</span>
    </div>

    <!-- TOC -->
    <div class="toc-card">
        <div class="toc-label">Table of Contents</div>
        <ol class="toc-list">
            <li><a href="#info-collect">Information We Collect</a></li>
            <li><a href="#info-use">How We Use Your Info</a></li>
            <li><a href="#info-share">Information Sharing</a></li>
            <li><a href="#data-storage">Data Storage & Security</a></li>
            <li><a href="#cookies">Cookies & Tracking</a></li>
            <li><a href="#your-rights">Your Rights</a></li>
            <li><a href="#third-party">Third Party Links</a></li>
            <li><a href="#contact-privacy">Contact Us</a></li>
        </ol>
    </div>

    <!-- S1 -->
    <section class="policy-section" id="info-collect">
        <div class="section-header">
            <div class="section-num">01</div>
            <h2 class="section-title">Information We Collect</h2>
        </div>
        <p>When you use our services or contact us, we may collect the following types of information:</p>
        <ul class="policy-list">
            <li><strong>Personal Information:</strong> Your name, phone number, email address, and pickup/drop location.</li>
            <li><strong>Booking Details:</strong> Trip date, time, vehicle preference, and payment information.</li>
            <li><strong>Device Information:</strong> Browser type, IP address, and device used to access our website.</li>
            <li><strong>Communication Data:</strong> Messages or queries sent via WhatsApp, phone, or email.</li>
        </ul>
        <div class="highlight-box">
            <strong>Note:</strong> We only collect information that is necessary to provide and improve our services. We do not collect sensitive personal data such as financial account numbers or government ID unless specifically required.
        </div>
    </section>

    <!-- S2 -->
    <section class="policy-section" id="info-use">
        <div class="section-header">
            <div class="section-num">02</div>
            <h2 class="section-title">How We Use Your Information</h2>
        </div>
        <p>The information we collect is used solely to deliver and improve our services. Specifically, we use your data to:</p>
        <ul class="policy-list">
            <li>Confirm and manage your car rental or cab bookings.</li>
            <li>Contact you regarding your booking status, updates, or changes.</li>
            <li>Process payments and issue receipts.</li>
            <li>Send important service-related notifications via SMS or WhatsApp.</li>
            <li>Improve our website, services, and customer experience.</li>
            <li>Respond to your inquiries and provide customer support.</li>
        </ul>
        <p>We will <strong>never</strong> use your personal information for unsolicited marketing without your consent.</p>
    </section>

    <!-- S3 -->
    <section class="policy-section" id="info-share">
        <div class="section-header">
            <div class="section-num">03</div>
            <h2 class="section-title">Information Sharing</h2>
        </div>
        <p>Surya Deep Car Rent does not sell, trade, or rent your personal information to third parties. We may share your data only in the following limited circumstances:</p>
        <ul class="policy-list">
            <li><strong>Drivers:</strong> Your name and contact number may be shared with the assigned driver for trip coordination.</li>
            <li><strong>Legal Requirements:</strong> If required by law, court order, or government authority.</li>
            <li><strong>Safety:</strong> To protect the rights, property, or safety of our staff, customers, or the public.</li>
        </ul>
        <div class="highlight-box">
            We ensure any party receiving your data maintains appropriate confidentiality and uses it only for the stated purpose.
        </div>
    </section>

    <!-- S4 -->
    <section class="policy-section" id="data-storage">
        <div class="section-header">
            <div class="section-num">04</div>
            <h2 class="section-title">Data Storage & Security</h2>
        </div>
        <p>We take the security of your personal information seriously and implement appropriate measures to protect it.</p>
        <ul class="policy-list">
            <li>Your data is stored on secured servers with restricted access.</li>
            <li>We use encryption and secure protocols where applicable.</li>
            <li>Booking records are retained for a period necessary to fulfill legal and business obligations.</li>
            <li>After the retention period, data is securely deleted or anonymized.</li>
        </ul>
        <div class="highlight-box">
            <strong>Important:</strong> While we take all reasonable steps to protect your data, no method of transmission over the internet is 100% secure. We cannot guarantee absolute security.
        </div>
    </section>

    <!-- S5 -->
    <section class="policy-section" id="cookies">
        <div class="section-header">
            <div class="section-num">05</div>
            <h2 class="section-title">Cookies & Tracking</h2>
        </div>
        <p>Our website may use cookies and similar tracking technologies to enhance your browsing experience.</p>
        <ul class="policy-list">
            <li>Cookies help us remember your preferences and improve site performance.</li>
            <li>We may use analytics tools to understand how visitors interact with our website.</li>
            <li>No personally identifiable information is collected through cookies without your knowledge.</li>
            <li>You can disable cookies through your browser settings at any time.</li>
        </ul>
        <p>Disabling cookies may affect certain features or functionality of our website.</p>
    </section>

    <!-- S6 -->
    <section class="policy-section" id="your-rights">
        <div class="section-header">
            <div class="section-num">06</div>
            <h2 class="section-title">Your Rights</h2>
        </div>
        <p>You have the following rights regarding your personal information held by us:</p>
        <ul class="policy-list">
            <li><strong>Access:</strong> Request a copy of the personal data we hold about you.</li>
            <li><strong>Correction:</strong> Request correction of inaccurate or incomplete data.</li>
            <li><strong>Deletion:</strong> Request deletion of your personal data (subject to legal obligations).</li>
            <li><strong>Objection:</strong> Object to the use of your data for specific purposes.</li>
            <li><strong>Withdrawal:</strong> Withdraw consent for data processing at any time.</li>
        </ul>
        <p>To exercise any of these rights, please contact us using the details at the bottom of this page.</p>
    </section>

    <!-- S7 -->
    <section class="policy-section" id="third-party">
        <div class="section-header">
            <div class="section-num">07</div>
            <h2 class="section-title">Third Party Links</h2>
        </div>
        <p>Our website may contain links to third-party websites such as Google Maps, WhatsApp, or payment gateways.</p>
        <ul class="policy-list">
            <li>We are not responsible for the privacy practices of third-party websites.</li>
            <li>Clicking on external links is at your own risk.</li>
            <li>We encourage you to review the privacy policies of any third-party services you use.</li>
        </ul>
        <div class="highlight-box">
            Surya Deep Car Rent has no control over and assumes no responsibility for the content or privacy practices of any third-party sites.
        </div>
    </section>

    <!-- S8 -->
    <section class="policy-section" id="contact-privacy">
        <div class="section-header">
            <div class="section-num">08</div>
            <h2 class="section-title">Contact Us</h2>
        </div>
        <p>If you have any questions, concerns, or requests regarding this Privacy Policy or your personal data, please reach out to us:</p>
        <ul class="policy-list">
            <li>Phone / WhatsApp: <strong>8128800558</strong></li>
            <li>Email: <strong>piyushvala42@gmail.com</strong></li>
            <li>Location: <strong>Amreli, Gujarat, India</strong></li>
        </ul>
        <p>We will respond to all privacy-related requests within <strong>7 business days</strong>.</p>
    </section>

    <!-- CONTACT CARD -->
    <div class="contact-card">
        <h3>Questions About Your Privacy?</h3>
        <p>We take your privacy seriously. Get in touch and we'll respond promptly.</p>
        <div class="contact-links">
            <a href="tel:8128800558" class="contact-btn contact-btn-primary">📞 8128800558</a>
            <a href="mailto:piyushvala42@gmail.com" class="contact-btn contact-btn-secondary">✉ piyushvala42@gmail.com</a>
        </div>
    </div>

</div>