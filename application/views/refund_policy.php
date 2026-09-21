<!-- HERO -->
<div class="vc-hero" style="margin-bottom: 48px;">
    <div class="vc-hero-inner">
        <div>
            <div class="vc-eyebrow">Surya Deep Car Rent</div>
            <h1>
                Refund <br>
                <em>Policy</em>
            </h1>
            <p class="vc-hero-sub">
                Understand our refund process, eligibility criteria, and how we handle cancellations and disputes fairly.
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
            <li><a href="#refund-overview">Overview</a></li>
            <li><a href="#eligibility">Refund Eligibility</a></li>
            <li><a href="#cancellation-refund">Cancellation & Refund Timeline</a></li>
            <li><a href="#non-refundable">Non-Refundable Cases</a></li>
            <li><a href="#refund-by-us">Cancellation By Us</a></li>
            <li><a href="#refund-process">Refund Process</a></li>
            <li><a href="#refund-mode">Refund Mode & Timeline</a></li>
            <li><a href="#refund-contact">Contact For Refund</a></li>
        </ol>
    </div>

    <!-- S1 -->
    <section class="policy-section" id="refund-overview">
        <div class="section-header">
            <div class="section-num">01</div>
            <h2 class="section-title">Overview</h2>
        </div>
        <p>At <strong>Surya Deep Car Rent</strong>, we strive to provide reliable and comfortable travel experiences. We understand that situations arise where you may need to cancel or modify your booking.</p>
        <p>This Refund Policy outlines the conditions under which refunds are issued, the process to request a refund, and the expected timelines for processing.</p>
        <div class="highlight-box">
            <strong>Our Commitment:</strong> We aim to handle all refund requests fairly, transparently, and within the shortest possible time. Your satisfaction is our priority.
        </div>
    </section>

    <!-- S2 -->
    <section class="policy-section" id="eligibility">
        <div class="section-header">
            <div class="section-num">02</div>
            <h2 class="section-title">Refund Eligibility</h2>
        </div>
        <p>A refund may be issued under the following conditions:</p>
        <ul class="policy-list">
            <li>You cancel your booking within the eligible cancellation window (see Section 3).</li>
            <li>Surya Deep Car Rent cancels your booking due to vehicle unavailability or operational issues.</li>
            <li>The service was not delivered as confirmed (e.g., driver did not show up).</li>
            <li>Duplicate payment was made for the same booking.</li>
            <li>Advance payment was collected but service was not rendered.</li>
        </ul>
        <div class="highlight-box">
            Refunds are applicable only on amounts paid in advance. Cash payments collected after trip completion are not eligible for refund unless a billing error is confirmed.
        </div>
    </section>

    <!-- S3 -->
    <section class="policy-section" id="cancellation-refund">
        <div class="section-header">
            <div class="section-num">03</div>
            <h2 class="section-title">Cancellation & Refund Timeline</h2>
        </div>
        <p>The refund amount depends on how far in advance you cancel your booking:</p>

        <!-- REFUND TABLE -->
        <div style="overflow-x:auto; margin: 20px 0;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:var(--ink); color:#fff;">
                        <th style="padding:12px 16px; text-align:left; border-radius:var(--radius-sm) 0 0 0;">Cancellation Time</th>
                        <th style="padding:12px 16px; text-align:center;">Refund %</th>
                        <th style="padding:12px 16px; text-align:left; border-radius:0 var(--radius-sm) 0 0;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background:var(--card-bg); border-bottom:1px solid var(--border);">
                        <td style="padding:12px 16px; color:var(--ink); font-weight:600;">24+ hours before trip</td>
                        <td style="padding:12px 16px; text-align:center; color:#28A745; font-weight:700; font-size:15px;">100%</td>
                        <td style="padding:12px 16px; color:var(--ink-soft);">Full refund of advance paid</td>
                    </tr>
                    <tr style="background:var(--cream); border-bottom:1px solid var(--border);">
                        <td style="padding:12px 16px; color:var(--ink); font-weight:600;">6 – 24 hours before trip</td>
                        <td style="padding:12px 16px; text-align:center; color:#F5A623; font-weight:700; font-size:15px;">50%</td>
                        <td style="padding:12px 16px; color:var(--ink-soft);">50% of advance amount refunded</td>
                    </tr>
                    <tr style="background:var(--card-bg); border-bottom:1px solid var(--border);">
                        <td style="padding:12px 16px; color:var(--ink); font-weight:600;">Less than 6 hours before trip</td>
                        <td style="padding:12px 16px; text-align:center; color:#E8232A; font-weight:700; font-size:15px;">0%</td>
                        <td style="padding:12px 16px; color:var(--ink-soft);">No refund applicable</td>
                    </tr>
                    <tr style="background:var(--cream);">
                        <td style="padding:12px 16px; color:var(--ink); font-weight:600;">No-show (without notice)</td>
                        <td style="padding:12px 16px; text-align:center; color:#E8232A; font-weight:700; font-size:15px;">0%</td>
                        <td style="padding:12px 16px; color:var(--ink-soft);">Full amount forfeited</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="highlight-box">
            <strong>How to Cancel:</strong> Call or WhatsApp us at <strong>8128800558</strong> with your booking reference number. Cancellations must be confirmed by our team to be valid.
        </div>
    </section>

    <!-- S4 -->
    <section class="policy-section" id="non-refundable">
        <div class="section-header">
            <div class="section-num">04</div>
            <h2 class="section-title">Non-Refundable Cases</h2>
        </div>
        <p>The following situations are <strong>not eligible</strong> for a refund:</p>
        <ul class="policy-list">
            <li>Cancellations made less than 6 hours before the scheduled trip time.</li>
            <li>No-show by the customer without prior cancellation notice.</li>
            <li>Trip terminated early by the customer's choice after the journey has begun.</li>
            <li>Damage charges levied due to misuse or damage caused to the vehicle.</li>
            <li>Additional charges such as tolls, parking fees, or waiting charges already incurred.</li>
            <li>Bookings made during peak seasons or festivals with a non-refundable advance clause.</li>
        </ul>
        <div class="highlight-box">
            <strong>Special Note:</strong> In cases of natural disasters, government-imposed curfews, or force majeure events, we will evaluate refund requests on a case-by-case basis with maximum fairness.
        </div>
    </section>

    <!-- S5 -->
    <section class="policy-section" id="refund-by-us">
        <div class="section-header">
            <div class="section-num">05</div>
            <h2 class="section-title">Cancellation By Us</h2>
        </div>
        <p>In rare cases, Surya Deep Car Rent may need to cancel a confirmed booking due to:</p>
        <ul class="policy-list">
            <li>Vehicle breakdown or mechanical failure with no replacement available.</li>
            <li>Driver unavailability due to emergency or illness.</li>
            <li>Extreme weather conditions or road closures making travel unsafe.</li>
            <li>Any other operational issue beyond our control.</li>
        </ul>
        <div class="highlight-box">
            <strong>Our Promise:</strong> If we cancel your confirmed booking for any reason, you will receive a <strong>100% full refund</strong> of all amounts paid. We will also make every effort to arrange an alternative vehicle where possible.
        </div>
    </section>

    <!-- S6 -->
    <section class="policy-section" id="refund-process">
        <div class="section-header">
            <div class="section-num">06</div>
            <h2 class="section-title">Refund Process</h2>
        </div>
        <p>To request a refund, please follow these steps:</p>
        <ul class="policy-list">
            <li>Contact us via phone or WhatsApp at <strong>8128800558</strong> with your booking details.</li>
            <li>Provide your booking reference number and reason for the refund request.</li>
            <li>Our team will review your request within <strong>2 business days</strong>.</li>
            <li>You will receive a confirmation of refund approval or rejection with reasons.</li>
            <li>Approved refunds will be processed within the timeline mentioned in Section 7.</li>
        </ul>
        <p>You may also email your refund request to <strong>piyushvala42@gmail.com</strong> with subject line: <em>"Refund Request – [Your Booking Reference]"</em>.</p>
    </section>

    <!-- S7 -->
    <section class="policy-section" id="refund-mode">
        <div class="section-header">
            <div class="section-num">07</div>
            <h2 class="section-title">Refund Mode & Timeline</h2>
        </div>
        <p>Refunds will be issued via the same payment method used for the original booking wherever possible.</p>
        <ul class="policy-list">
            <li><strong>UPI / Bank Transfer:</strong> Refund processed within 3–5 business days.</li>
            <li><strong>Cash Payment:</strong> Cash refund arranged in person or via hand delivery within 2 business days.</li>
            <li><strong>Online Payment Gateway:</strong> Refund processed within 5–7 business days depending on your bank.</li>
        </ul>
        <div class="highlight-box">
            <strong>Please Note:</strong> Actual credit to your account may take additional time depending on your bank or payment provider. Surya Deep Car Rent is not responsible for delays caused by third-party payment processors.
        </div>
    </section>

    <!-- S8 -->
    <section class="policy-section" id="refund-contact">
        <div class="section-header">
            <div class="section-num">08</div>
            <h2 class="section-title">Contact For Refund</h2>
        </div>
        <p>For any refund-related queries or to initiate a refund request, please contact us directly:</p>
        <ul class="policy-list">
            <li>Phone / WhatsApp: <strong>8128800558</strong></li>
            <li>Email: <strong>piyushvala42@gmail.com</strong></li>
            <li>Business Hours: <strong>Monday – Sunday, 7:00 AM – 9:00 PM</strong></li>
            <li>Location: <strong>Amreli, Gujarat, India</strong></li>
        </ul>
        <p>We aim to resolve all refund queries within <strong>3–5 business days</strong> from the date of request.</p>
    </section>

    <!-- CONTACT CARD -->
    <div class="contact-card">
        <h3>Need a Refund or Have a Query?</h3>
        <p>Reach out to us directly — we're here to help resolve your concern quickly and fairly.</p>
        <div class="contact-links">
            <a href="tel:8128800558" class="contact-btn contact-btn-primary">📞 8128800558</a>
            <a href="mailto:piyushvala42@gmail.com" class="contact-btn contact-btn-secondary">✉ piyushvala42@gmail.com</a>
        </div>
    </div>

</div>