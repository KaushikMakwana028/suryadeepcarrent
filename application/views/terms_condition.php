<!-- HERO -->
<div class="vc-hero" style="margin-bottom: 48px;">
    <div class="vc-hero-inner">
        <div>
            <div class="vc-eyebrow">Surya Deep Car Rent</div>
            <h1>
                Terms &amp; <br>
                <em>Conditions</em>
            </h1>
            <p class="vc-hero-sub">
                Please read these terms carefully before booking or using our car rental and cab services.
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

<!-- STYLES (page-specific only) -->
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
            <li><a href="#acceptance">Acceptance of Terms</a></li>
            <li><a href="#booking">Booking & Reservations</a></li>
            <li><a href="#payment">Payment Policy</a></li>
            <li><a href="#cancellation">Cancellation Policy</a></li>
            <li><a href="#driver">Driver Responsibilities</a></li>
            <li><a href="#vehicle">Vehicle Usage</a></li>
            <li><a href="#liability">Liability</a></li>
            <li><a href="#dispute">Dispute Resolution</a></li>
        </ol>
    </div>

    <!-- S1 -->
    <section class="policy-section" id="acceptance">
        <div class="section-header">
            <div class="section-num">01</div>
            <h2 class="section-title">Acceptance of Terms</h2>
        </div>
        <p>By booking or using any service provided by <strong>Surya Deep Car Rent</strong>, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our services.</p>
        <p>These terms apply to all car rentals, cab bookings, airport pickups, local rides, and outstation travel arranged through Surya Deep Car Rent.</p>
        <div class="highlight-box">
            <strong>Note:</strong> We reserve the right to update these terms at any time. Continued use of our services after changes implies acceptance of the revised terms.
        </div>
    </section>

    <!-- S2 -->
    <section class="policy-section" id="booking">
        <div class="section-header">
            <div class="section-num">02</div>
            <h2 class="section-title">Booking & Reservations</h2>
        </div>
        <p>All bookings must be made in advance via phone, WhatsApp, or our website. Bookings are confirmed only after you receive a confirmation message from us.</p>
        <ul class="policy-list">
            <li>Bookings are subject to vehicle availability at the time of confirmation.</li>
            <li>Provide accurate pickup location, destination, date, and time while booking.</li>
            <li>Incorrect information may result in delays or cancellation without refund.</li>
            <li>We reserve the right to refuse service at our discretion.</li>
            <li>A booking confirmation number will be shared upon successful reservation.</li>
        </ul>
    </section>

    <!-- S3 -->
    <section class="policy-section" id="payment">
        <div class="section-header">
            <div class="section-num">03</div>
            <h2 class="section-title">Payment Policy</h2>
        </div>
        <p>All payments must be made in Indian Rupees (INR). We accept cash, UPI, bank transfer, and other digital payment methods.</p>
        <ul class="policy-list">
            <li>Full payment or advance as agreed must be made before the trip begins.</li>
            <li>Additional charges (tolls, parking, night charges) are to be paid by the customer.</li>
            <li>Fuel costs for outstation trips are included as per the agreed rate.</li>
            <li>Receipts will be provided upon request.</li>
        </ul>
        <div class="highlight-box">
            <strong>Outstation Trips:</strong> A minimum advance payment may be required to confirm long-distance bookings.
        </div>
    </section>

    <!-- S4 -->
    <section class="policy-section" id="cancellation">
        <div class="section-header">
            <div class="section-num">04</div>
            <h2 class="section-title">Cancellation Policy</h2>
        </div>
        <p>We understand plans change. Please notify us as early as possible if you need to cancel.</p>
        <ul class="policy-list">
            <li>Cancellations made 24+ hours before the trip: Full refund or no charge.</li>
            <li>Cancellations made 6–24 hours before: 50% of the booking amount may be charged.</li>
            <li>Cancellations made less than 6 hours before: Full booking amount may be charged.</li>
            <li>No-shows without prior notice will be charged the full amount.</li>
            <li>Cancellation by us due to unforeseen circumstances: full refund will be issued.</li>
        </ul>
    </section>

    <!-- S5 -->
    <section class="policy-section" id="driver">
        <div class="section-header">
            <div class="section-num">05</div>
            <h2 class="section-title">Driver Responsibilities</h2>
        </div>
        <p>Our drivers are trained, verified, and licensed professionals committed to your safety and comfort.</p>
        <ul class="policy-list">
            <li>Drivers will arrive at the pickup location on time as confirmed.</li>
            <li>Drivers will follow all traffic rules and safety regulations.</li>
            <li>Passengers must cooperate with the driver and follow safety instructions.</li>
            <li>Abusive behavior towards the driver will result in trip termination without refund.</li>
        </ul>
    </section>

    <!-- S6 -->
    <section class="policy-section" id="vehicle">
        <div class="section-header">
            <div class="section-num">06</div>
            <h2 class="section-title">Vehicle Usage</h2>
        </div>
        <p>Vehicles provided by Surya Deep Car Rent must be used responsibly and within agreed terms.</p>
        <ul class="policy-list">
            <li>Smoking, alcohol, or carrying illegal items inside vehicles is strictly prohibited.</li>
            <li>Passengers are responsible for any damage caused to the vehicle during the trip.</li>
            <li>Vehicle must not be used for purposes other than agreed.</li>
            <li>Maximum passenger capacity as per vehicle registration must not be exceeded.</li>
            <li>Pets are allowed only with prior permission.</li>
        </ul>
    </section>

    <!-- S7 -->
    <section class="policy-section" id="liability">
        <div class="section-header">
            <div class="section-num">07</div>
            <h2 class="section-title">Liability</h2>
        </div>
        <p>Surya Deep Car Rent shall not be held liable for delays or failures caused by factors beyond our control including:</p>
        <ul class="policy-list">
            <li>Traffic conditions, road blocks, or natural disasters.</li>
            <li>Vehicle breakdown due to unforeseen mechanical issues.</li>
            <li>Loss or damage to passenger luggage or belongings.</li>
            <li>Acts of God, strikes, or government restrictions.</li>
        </ul>
        <div class="highlight-box">
            Our maximum liability shall not exceed the amount paid for the specific booking in question.
        </div>
    </section>

    <!-- S8 -->
    <section class="policy-section" id="dispute">
        <div class="section-header">
            <div class="section-num">08</div>
            <h2 class="section-title">Dispute Resolution</h2>
        </div>
        <p>Any disputes shall first be resolved through direct communication. Please contact us at:</p>
        <ul class="policy-list">
            <li>Phone / WhatsApp: <strong>8128800558</strong></li>
            <li>Email: <strong>piyushvala42@gmail.com</strong></li>
        </ul>
        <p>If unresolved, disputes shall be subject to the jurisdiction of courts in <strong>Ahmedabad, Gujarat, India</strong>.</p>
    </section>

    <!-- CONTACT -->
    <div class="contact-card">
        <h3>Have Questions?</h3>
        <p>Our team is happy to help. Reach out anytime for assistance with your booking or queries.</p>
        <div class="contact-links">
            <a href="tel:8128800558" class="contact-btn contact-btn-primary">📞 8128800558</a>
            <a href="mailto:piyushvala42@gmail.com" class="contact-btn contact-btn-secondary">✉ piyushvala42@gmail.com</a>
        </div>
    </div>

</div>