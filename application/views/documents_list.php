<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$document_map = isset($document_map) ? $document_map : array();
$required_types = isset($required_types) ? $required_types : array();
$booking = !empty($booking) ? $booking : array();
$can_continue_to_payment = !empty($can_continue_to_payment);
$current_step = isset($current_step) ? (int) $current_step : 2;
$cancel_url = isset($cancel_url) ? $cancel_url : base_url('dashboard');
$booking_back_url = base_url('bookings/create' . (!empty($booking['vehicle_id']) ? '?vehicle_id=' . (int) $booking['vehicle_id'] : ''));
$booking_back_url .= (strpos($booking_back_url, '?') === false ? '?' : '&') . 'booking_id=' . (int) $booking['id'] . '&customer_id=' . (int) $booking['customer_id'];
?>

<style>
    .step-shell {
        padding: 20px 24px;
    }

    .stepper {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .step-item {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .step-badge {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        border: 1px solid rgba(35, 94, 167, .18);
        background: #fff;
        color: var(--muted);
    }

    .step-item.active .step-badge,
    .step-item.done .step-badge {
        background: linear-gradient(135deg, var(--accent) 0%, #f1c14f 100%);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 8px 18px rgba(35, 94, 167, .18);
    }

    .step-label {
        color: var(--muted);
        font-weight: 700;
    }

    .step-item.active .step-label,
    .step-item.done .step-label {
        color: var(--ink);
    }

    .step-line {
        width: 42px;
        height: 2px;
        border-radius: 999px;
        background: rgba(35, 94, 167, .14);
    }

    .step-line.done {
        background: linear-gradient(90deg, var(--accent) 0%, #f1c14f 100%);
    }

    .booking-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .booking-actions .btn,
    .booking-actions .btn-secondary {
        min-width: 156px;
    }

    /* Premium File Input Styling */
    input[type="file"] {
        border: 1.5px dashed var(--border-md);
        padding: 8px 12px;
        border-radius: var(--r-md);
        background: #fdfcf7;
        cursor: pointer;
        min-height: unset;
        font-size: 13px;
        color: var(--ink-2);
        width: 100%;
        display: block;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    input[type="file"]::file-selector-button {
        background: var(--accent);
        border: none;
        color: #fff;
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        margin-right: 12px;
        transition: background 0.15s ease;
    }

    input[type="file"]::file-selector-button:hover {
        background: var(--accent-dark);
    }

    @media (max-width: 600px) {
        .booking-actions {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .booking-actions .btn,
        .booking-actions .btn-secondary {
            width: 100%;
            min-width: unset;
        }
    }

    /* ══════════════════════════════════════════════════════════════
       Terms & Conditions Modal — v2, document-style, mobile-first
       ══════════════════════════════════════════════════════════════ */
    .tc-modal-overlay {
        --tc-radius: 20px;
        --tc-border: rgba(23, 53, 92, 0.10);
        --tc-warn-bg: #fffaf0;
        --tc-warn-border: #f0c96b;
        --tc-warn-num: #92400e;
        --tc-agree-bg: #f3f8ff;
        --tc-agree-border: #90bdf2;
        --tc-agree-num: #1e40af;

        position: fixed;
        inset: 0;
        background: rgba(12, 24, 43, 0.7);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 28px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.22s ease, visibility 0.22s ease;
    }

    .tc-modal-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    .tc-modal-card {
        background: #ffffff;
        border-radius: var(--tc-radius);
        width: 100%;
        max-width: 700px;
        height: min(90vh, 900px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 40px 90px -20px rgba(12, 24, 43, 0.45), 0 2px 10px rgba(12, 24, 43, 0.08);
        border: 1px solid rgba(12, 24, 43, 0.06);
        transform: scale(0.97) translateY(12px);
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .tc-modal-overlay.open .tc-modal-card {
        transform: scale(1) translateY(0);
    }

    /* Mobile-only drag handle */
    .tc-drag-handle {
        display: none;
        width: 40px;
        height: 4px;
        border-radius: 999px;
        background: rgba(15, 32, 56, 0.18);
        margin: 10px auto 0;
        flex-shrink: 0;
    }

    /* ── Header ── */
    .tc-modal-head {
        padding: 18px 22px 0;
        background: #ffffff;
        flex-shrink: 0;
        position: relative;
    }

    .tc-head-top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .tc-head-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .tc-icon-badge {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eaf2ff 0%, #d9e9ff 100%);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        border: 1px solid rgba(35, 94, 167, 0.16);
        flex-shrink: 0;
    }

    .tc-head-titles {
        min-width: 0;
    }

    .tc-head-titles h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .tc-head-titles p {
        margin: 2px 0 0;
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
        overflow-wrap: anywhere;
    }

    .tc-modal-close-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid var(--tc-border);
        background: #f8fafc;
        color: var(--ink-2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1;
        flex-shrink: 0;
    }

    .tc-modal-close-btn:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* Toolbar row: language switch + live progress, one line, compact */
    .tc-toolbar-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .tc-lang-switcher {
        display: inline-flex;
        background: #eef2f7;
        padding: 3px;
        border-radius: 999px;
        border: 1px solid var(--tc-border);
    }

    .tc-lang-btn {
        border: none;
        background: transparent;
        padding: 6px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-2);
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .tc-lang-btn.active {
        background: var(--accent);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(35, 94, 167, 0.3);
    }

    .tc-progress-text {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        white-space: nowrap;
    }

    .tc-progress-text b {
        color: var(--accent);
    }

    /* Thin scroll-progress bar under the toolbar */
    .tc-progress-track {
        margin-top: 12px;
        height: 4px;
        border-radius: 999px;
        background: rgba(35, 94, 167, 0.10);
        overflow: hidden;
    }

    .tc-progress-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--accent) 0%, #f1c14f 100%);
        border-radius: 999px;
        transition: width 0.15s ease-out;
    }

    /* ── Scrollable body — this is the main reading area, now much larger ── */
    .tc-modal-body {
        padding: 20px 22px 14px;
        overflow-y: auto;
        flex: 1;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(35, 94, 167, 0.28) transparent;
    }

    .tc-modal-body::-webkit-scrollbar {
        width: 7px;
    }

    .tc-modal-body::-webkit-scrollbar-thumb {
        background: rgba(35, 94, 167, 0.28);
        border-radius: 999px;
    }

    .tc-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    /* ── Points list — cleaner "document clause" styling, bigger type ── */
    .tc-points-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding-bottom: 8px;
    }

    .tc-point-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 4px;
        border-bottom: 1px solid rgba(23, 53, 92, 0.08);
        animation: tc-fade-in 0.32s ease both;
    }

    .tc-point-card:last-child {
        border-bottom: none;
    }

    .tc-point-num {
        min-width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f3f6fb;
        color: var(--accent);
        font-weight: 800;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(35, 94, 167, 0.14);
        margin-top: 1px;
    }

    .tc-point-text {
        font-size: 15.5px;
        line-height: 1.75;
        color: var(--ink-2);
        margin: 0;
        flex: 1;
        overflow-wrap: anywhere;
        letter-spacing: 0.1px;
    }

    .tc-point-text strong {
        color: var(--ink);
        font-weight: 700;
    }

    .tc-point-text em {
        color: var(--muted);
        font-style: italic;
    }

    /* Highlighted Point 10 (Deposit) — kept as a distinct callout card */
    .tc-point-card.tc-point-warn {
        background: var(--tc-warn-bg);
        border: 1.5px solid var(--tc-warn-border);
        border-radius: 14px;
        padding: 16px;
        margin: 6px 0;
    }

    .tc-point-card.tc-point-warn .tc-point-num {
        background: #fef3c7;
        color: var(--tc-warn-num);
        border-color: #fcd34d;
    }

    /* Highlighted Point 11 (Declaration) — kept as a distinct callout card */
    .tc-point-card.tc-point-agree {
        background: var(--tc-agree-bg);
        border: 1.5px solid var(--tc-agree-border);
        border-radius: 14px;
        padding: 16px;
        margin: 6px 0 0;
    }

    .tc-point-card.tc-point-agree .tc-point-num {
        background: #dbeafe;
        color: var(--tc-agree-num);
        border-color: #93c5fd;
    }

    @keyframes tc-fade-in {
        from {
            opacity: 0;
            transform: translateY(4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Small "scroll to read more" cue, shown until user scrolls near bottom */
    .tc-scroll-cue {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin: 4px 0 2px;
        padding: 8px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--accent);
        opacity: 1;
        transition: opacity 0.25s ease;
        pointer-events: none;
    }

    .tc-scroll-cue.hidden {
        opacity: 0;
    }

    .tc-scroll-cue svg {
        animation: tc-bounce 1.4s ease-in-out infinite;
    }

    @keyframes tc-bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(3px);
        }
    }

    /* ── Sticky footer ── */
    .tc-modal-foot {
        padding: 14px 22px;
        padding-bottom: calc(14px + env(safe-area-inset-bottom, 0px));
        background: #f8fafd;
        border-top: 1px solid var(--tc-border);
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex-shrink: 0;
    }

    .tc-agree-card {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 14px;
        border-radius: 12px;
        background: #ffffff;
        border: 1.5px solid rgba(23, 53, 92, 0.15);
        cursor: pointer;
        user-select: none;
        transition: all 0.18s ease;
    }

    .tc-agree-card:hover {
        border-color: var(--accent);
        background: #fdfcf7;
    }

    .tc-agree-card.checked {
        border-color: var(--accent);
        background: #eff6ff;
        box-shadow: 0 2px 10px rgba(35, 94, 167, 0.12);
    }

    .tc-agree-card.disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .tc-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--accent);
        margin-top: 2px;
        flex-shrink: 0;
    }

    .tc-agree-card.disabled .tc-checkbox {
        cursor: not-allowed;
    }

    .tc-agree-label {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.5;
        cursor: pointer;
        margin: 0;
    }

    .tc-agree-hint {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        margin-top: 3px;
    }

    .tc-foot-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .tc-btn-continue {
        background: linear-gradient(135deg, var(--accent) 0%, #163f72 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        height: 48px;
        padding: 0 22px;
        font-size: 13.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(35, 94, 167, 0.32);
        transition: all 0.2s ease;
        opacity: 0.4;
        pointer-events: none;
        white-space: nowrap;
    }

    .tc-btn-continue.active {
        opacity: 1;
        pointer-events: auto;
        cursor: pointer;
    }

    .tc-btn-continue.active:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(35, 94, 167, 0.45);
    }

    .tc-btn-cancel {
        background: #ffffff;
        color: var(--ink-2);
        border: 1px solid rgba(23, 53, 92, 0.18);
        border-radius: 10px;
        height: 48px;
        padding: 0 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .tc-btn-cancel:hover {
        background: #f1f5f9;
        color: var(--ink);
    }

    /* ══════════════════════════════════════════════
       Mobile — near-full-screen sheet, safe-area aware
       ══════════════════════════════════════════════ */
    @media (max-width: 640px) {
        .tc-modal-overlay {
            padding: 0;
            align-items: flex-end;
        }

        .tc-modal-card {
            max-width: 100%;
            height: 94vh;
            height: 94dvh;
            border-radius: 20px 20px 0 0;
            transform: translateY(100%);
        }

        .tc-modal-overlay.open .tc-modal-card {
            transform: translateY(0);
        }

        .tc-drag-handle {
            display: block;
        }

        .tc-modal-head {
            padding: 10px 16px 0;
        }

        .tc-head-brand {
            gap: 10px;
        }

        .tc-icon-badge {
            width: 36px;
            height: 36px;
            font-size: 17px;
            border-radius: 10px;
        }

        .tc-head-titles h3 {
            font-size: 14.5px;
        }

        .tc-head-titles p {
            font-size: 10.5px;
        }

        .tc-toolbar-row {
            margin-top: 10px;
        }

        .tc-lang-btn {
            padding: 6px 11px;
            font-size: 11.5px;
        }

        .tc-progress-text {
            font-size: 11px;
        }

        .tc-progress-track {
            margin-top: 10px;
        }

        .tc-modal-body {
            padding: 16px 16px 8px;
        }

        .tc-point-card {
            padding: 14px 2px;
            gap: 12px;
        }

        .tc-point-card.tc-point-warn,
        .tc-point-card.tc-point-agree {
            padding: 14px;
        }

        .tc-point-num {
            min-width: 30px;
            height: 30px;
            font-size: 12px;
            border-radius: 9px;
        }

        .tc-point-text {
            font-size: 14.5px;
            line-height: 1.68;
        }

        .tc-modal-foot {
            padding: 12px 16px;
            padding-bottom: calc(12px + env(safe-area-inset-bottom, 0px));
            gap: 10px;
        }

        .tc-agree-card {
            padding: 11px 12px;
        }

        .tc-agree-label {
            font-size: 12.5px;
            line-height: 1.45;
        }

        .tc-foot-actions {
            flex-direction: column-reverse;
            width: 100%;
            gap: 8px;
        }

        .tc-btn-continue,
        .tc-btn-cancel {
            width: 100%;
            min-width: unset;
            justify-content: center;
            height: 48px;
            font-size: 13.5px;
        }
    }

    /* Very small phones */
    @media (max-width: 360px) {
        .tc-head-titles p {
            display: none;
        }
    }
</style>

<section class="section-card step-shell">
    <div class="stepper">
        <?php
        $steps = array(
            1 => 'Booking',
            2 => 'Document',
            3 => 'Payment',
        );
        foreach ($steps as $step_no => $step_label):
            $is_active = $current_step === $step_no;
            $is_done = $current_step > $step_no;
        ?>
            <div class="step-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $is_done ? 'done' : ''; ?>">
                <div class="step-badge">
                    <?php echo $is_done ? '&#10003;' : $step_no; ?>
                </div>
                <div class="step-label">
                    <?php echo html_escape($step_label); ?>
                </div>
            </div>
            <?php if ($step_no < count($steps)): ?>
                <div class="step-line <?php echo $current_step > $step_no ? 'done' : ''; ?>"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<div class="split-grid">
    <section class="section-card">
        <div class="card-head">
            <div>
                <div class="eyebrow">Documents</div>
                <h3>Upload the required files.</h3>
                <p>Add Aadhaar Card and Driving License as image or PDF files for this booking.</p>
            </div>
        </div>

        <div class="info-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin-bottom:20px;">
            <?php foreach ($required_types as $document_type): ?>
                <?php $document = isset($document_map[$document_type]) ? $document_map[$document_type] : array('status' => 'missing'); ?>
                <div class="feature-card">
                    <strong><?php echo html_escape($document_type); ?></strong>
                    <span>
                        Status:
                        <?php echo !empty($document['file_path']) ? html_escape(ucfirst($document['status'])) : 'Not uploaded'; ?>
                    </span>
                    <?php if (!empty($document['admin_notes'])): ?>
                        <span>Note: <?php echo html_escape($document['admin_notes']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($document['file_path'])): ?>
                        <div class="hero-actions" style="margin-top:12px;">
                            <a class="btn-secondary" href="<?php echo base_url($document['file_path']); ?>" target="_blank">View</a>
                            <a class="btn-secondary js-swal-confirm" href="<?php echo base_url('documents/delete/' . (int) $document['id'] . '?customer_id=' . (int) $booking['customer_id']); ?>" data-swal-title="Delete document?" data-swal-text="This uploaded document will be removed." data-swal-confirm="Delete">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post" action="<?php echo base_url('documents/store'); ?>" enctype="multipart/form-data" id="documentUploadForm">
            <input type="hidden" name="booking_id" value="<?php echo (int) $booking['id']; ?>">
            <input type="hidden" name="customer_id" value="<?php echo (int) $booking['customer_id']; ?>">
            <div class="form-grid">
                <?php
                $field_map = array('aadhaar_file' => 'Aadhaar Card', 'driving_license_file' => 'Driving License');
                foreach ($field_map as $field_name => $document_type):
                    $has_file = !empty($document_map[$document_type]['file_path']);
                ?>
                    <div>
                        <label><?php echo $document_type; ?></label>
                        <?php if ($has_file): ?>
                            <p style="color:#1a8a4a;font-size:13px;font-weight:600;margin:4px 0;">
                                ✓ Already uploaded &nbsp;
                                <!-- <a href="<?php echo base_url($document_map[$document_type]['file_path']); ?>" target="_blank"
                                    style="color:var(--accent);font-weight:600;">View</a> -->
                            </p>
                            <label style="display:inline-flex;align-items:center;gap:6px;margin-top:6px;font-size:13px;color:#666;cursor:pointer;">
                                <input type="checkbox" style="width:15px;height:15px;cursor:pointer;accent-color:var(--accent);"
                                    onchange="document.getElementById('<?php echo $field_name; ?>').style.display=this.checked?'block':'none'">
                                Replace document
                            </label>
                            <input type="file" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>"
                                accept=".jpg,.jpeg,.png,.pdf"
                                style="display:none;margin-top:8px;font-size:13px;">
                        <?php else: ?>
                            <input type="file" name="<?php echo $field_name; ?>" accept=".jpg,.jpeg,.png,.pdf" required>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="booking-actions">
                <a class="btn-secondary" href="<?php echo $booking_back_url; ?>">Previous Step</a>
                <?php
                $all_uploaded = !empty($document_map['Aadhaar Card']['file_path']) && !empty($document_map['Driving License']['file_path']);
                $payment_continue_url = base_url('documents/complete/' . (int)$booking['id'] . '?customer_id=' . (int)$booking['customer_id']);
                ?>
                <?php if ($all_uploaded): ?>
                    <button type="button" class="btn" id="btnContinueToPayment" data-target-url="<?php echo html_escape($payment_continue_url); ?>">Continue to Payment &rarr;</button>
                <?php else: ?>
                    <button class="btn" type="submit" id="btnUploadAndContinue">Upload Documents &amp; Continue &rarr;</button>
                <?php endif; ?>
                <a class="btn-secondary js-swal-confirm" href="<?php echo $cancel_url; ?>" data-swal-title="Cancel booking?" data-swal-text="This incomplete booking draft will be removed." data-swal-confirm="Yes, cancel">Cancel</a>
            </div>
        </form>
    </section>

    <aside class="section-card accent-card">
        <div class="eyebrow">Booking</div>
        <div class="card-head">
            <div>
                <h3>Current booking summary.</h3>
                <p>Upload both documents to proceed to the payment step.</p>
            </div>
        </div>
        <div class="info-grid" style="grid-template-columns:1fr;">
            <div class="feature-card">
                <strong><?php echo html_escape($booking['booking_code']); ?></strong>
                <span><?php echo html_escape($booking['vehicle_name']); ?></span>
            </div>
            <div class="feature-card">
                <strong>Trip</strong>
                <span><?php echo html_escape($booking['trip_label']); ?></span>
            </div>
            <div class="feature-card">
                <strong>Route</strong>
                <span><?php echo html_escape($booking['trip_route']); ?></span>
            </div>
        </div>
    </aside>
</div>

<!-- ── Terms & Conditions Modal Overlay ── -->
<div class="tc-modal-overlay" id="tcModalOverlay" role="dialog" aria-modal="true" aria-labelledby="tcTitle">
    <div class="tc-modal-card">
        <div class="tc-drag-handle" aria-hidden="true"></div>

        <!-- Header -->
        <div class="tc-modal-head">
            <div class="tc-head-top-row">
                <div class="tc-head-brand">
                    <div class="tc-icon-badge">
                        <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="tc-head-titles">
                        <h3 id="tcTitle">Terms &amp; Conditions of Car Rental</h3>
                        <p id="tcSubtitle">Surya Deep Car Rent, Amreli &bull; Booking <?php echo html_escape(!empty($booking['booking_code']) ? $booking['booking_code'] : ''); ?></p>
                    </div>
                </div>
                <button type="button" class="tc-modal-close-btn" id="tcCloseBtn" aria-label="Close modal">&times;</button>
            </div>

            <div class="tc-toolbar-row">
                <div class="tc-lang-switcher">
                    <button type="button" class="tc-lang-btn active" id="tcLangEn" onclick="switchTcLang('en')">
                        <span>🇬🇧</span> English
                    </button>
                    <button type="button" class="tc-lang-btn" id="tcLangGu" onclick="switchTcLang('gu')">
                        <span>🇮🇳</span> ગુજરાતી
                    </button>
                </div>
                <div class="tc-progress-text" id="tcProgressText">Clause <b>1</b> of 11</div>
            </div>

            <div class="tc-progress-track">
                <div class="tc-progress-fill" id="tcProgressFill"></div>
            </div>
        </div>

        <!-- Scrollable body — the main reading area -->
        <div class="tc-modal-body" id="tcModalBody">
            <div class="tc-points-list" id="tcPointsContainer">
                <!-- Rendered dynamically via JS -->
            </div>
            <div class="tc-scroll-cue" id="tcScrollCue">
                <span id="tcScrollCueText">Scroll to read all terms</span>
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
        </div>

        <!-- Footer -->
        <div class="tc-modal-foot">
            <!-- Agreement Checkbox -->
            <label class="tc-agree-card" id="tcAgreeCard">
                <input type="checkbox" class="tc-checkbox" id="tcAgreeCheckbox">
                <span>
                    <span class="tc-agree-label" id="tcAgreeLabel">
                        I have read, understood, and agree to all 11 Terms &amp; Conditions of Surya Deep Car Rent, Amreli.
                    </span>
                    <span class="tc-agree-hint" id="tcAgreeHint" style="display:none;">Scroll to the end of the terms to enable this.</span>
                </span>
            </label>

            <!-- Buttons -->
            <div class="tc-foot-actions">
                <button type="button" class="tc-btn-cancel" id="tcCancelBtn">Cancel</button>
                <button type="button" class="tc-btn-continue" id="tcSubmitBtn">
                    <span id="tcSubmitBtnText">Accept &amp; Continue to Payment &rarr;</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var tcData = {
            en: {
                title: "Terms & Conditions of Car Rental",
                subtitle: "Surya Deep Car Rent, Amreli • Booking <?php echo html_escape(!empty($booking['booking_code']) ? $booking['booking_code'] : ''); ?>",
                agreeLabel: "I have read, understood, and agree to all 11 Terms & Conditions of Surya Deep Car Rent, Amreli.",
                agreeHint: "Scroll to the end of the terms to enable this.",
                scrollCue: "Scroll to read all terms",
                clauseWord: "Clause",
                ofWord: "of",
                btnContinue: "Accept & Continue to Payment →",
                btnCancel: "Cancel",
                alertAgree: "Please agree to the Terms & Conditions before proceeding.",
                alertScroll: "Please scroll through and read all terms before agreeing.",
                points: [{
                        num: "01",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> Only a person holding a valid driving license and having submitted the required documents will be allowed to drive the vehicle."
                    },
                    {
                        num: "02",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> In the event of any accident while driving the vehicle, the responsibility shall lie with the person who has rented the vehicle as well as the person driving the vehicle. Any financial loss shall be borne by the person who has rented the vehicle. Any kind of damage to the vehicle shall be repaired only at the showroom."
                    },
                    {
                        num: "03",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> The vehicle shall be used only for convenience purposes. If the vehicle is used for any illegal activity or any unlawful act is committed using the vehicle, legal action shall be taken."
                    },
                    {
                        num: "04",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> The vehicle owner shall have no responsibility whatsoever for any loss of life, death, or any other physical, financial, or mental loss/injury caused while driving the vehicle."
                    },
                    {
                        num: "05",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> The vehicle shall be thoroughly checked before it is driven. Any existing damage/defect noticed at that time shall be brought to our attention. When the vehicle is returned, it will be checked by us, and if any damage/defect is found, the person who rented the vehicle shall be responsible for paying its cost."
                    },
                    {
                        num: "06",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> While driving the vehicle, proper care shall be taken to fully comply with all laws. We will not provide any legal assistance against you in the event of a violation of the law. Therefore, the entire responsibility shall remain with the person who has rented the vehicle."
                    },
                    {
                        num: "07",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> If any difficulty is experienced while driving the vehicle, or if any other problem arises, we must be informed first."
                    },
                    {
                        num: "08",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> The booking amount of the vehicle shall not be refunded, and the date shall not be changed."
                    },
                    {
                        num: "09",
                        text: "<strong>Surya Deep Car Rent, Amreli:</strong> This is a private organization legally engaged in business and commercial activities. Any act intended to cause harm to the name or reputation of the organization/company shall be considered an offence. Legal action shall be taken against such an act."
                    },
                    {
                        num: "10",
                        text: "If, for any reason, the vehicle is seized by the police or seized in any manner, the person who has rented the vehicle shall have to pay <strong>₹1,00,000/- as a deposit</strong>. When the vehicle is released, the vehicle rental charges and any other applicable expenses shall be deducted, and the remaining amount shall be refunded. <em>(The responsibility for getting the vehicle released shall remain with the person who rented the vehicle.)</em>",
                        isWarn: true
                    },
                    {
                        num: "11",
                        text: "We hereby confirm in writing that we have read, understood, and carefully considered all the above-mentioned rules and regulations with a clear and sound mind, and we agree to comply with all of them. If any mistake occurs knowingly, the responsibility shall be ours. We give written permission to the owner to take legal action against us, and we shall cooperate in such proceedings.",
                        isAgree: true
                    }
                ]
            },
            gu: {
                title: "વાહન ભાડે આપવાના નીતિ-નિયમો અને શરતો",
                subtitle: "સૂર્ય દીપ કાર રેન્ટ, અમરેલી • બુકિંગ <?php echo html_escape(!empty($booking['booking_code']) ? $booking['booking_code'] : ''); ?>",
                agreeLabel: "અમો ઉપરના તમામ ૧૧ નીતિ-નિયમો શુદ્ધબુદ્ધિથી શાંતિથી સમજી, વિચારીને આ તમામનું પાલન કરવાની બાંહેધરી આપીએ છીએ.",
                agreeHint: "આગળ વધવા માટે તમામ નિયમો સુધી સ્ક્રોલ કરો.",
                scrollCue: "તમામ નિયમો વાંચવા સ્ક્રોલ કરો",
                clauseWord: "કલમ",
                ofWord: "માંથી",
                btnContinue: "સ્વીકારો અને પેમેન્ટ કરો →",
                btnCancel: "રદ કરો",
                alertAgree: "કૃપા કરીને આગળ વધતા પહેલા નીતિ-નિયમો સ્વીકારો.",
                alertScroll: "કૃપા કરીને સંમતિ આપતા પહેલા તમામ નિયમો વાંચો.",
                points: [{
                        num: "૦૧",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> માત્ર લાયસન્સ ધરાવનાર વ્યક્તિને એ પણ જમા કરાવેલ ડોકયુમેન્ટવાળા વ્યક્તિને જ વાહન ચલાવવા મળશે."
                    },
                    {
                        num: "૦૨",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> ના કોઈ પણ વાહન ચલાવતી વખતે અકસ્માતની જવાબદારી વાહન ભાડે લઈ જનાર તથા વાહન ચલાવનાર વ્યક્તિઓની રહેશે. આર્થિક નુકશાન ભાડે લઈ જનાર વ્યક્તિને ભોગવવાની રહેશે. વાહનના કોઈપણ જાતનું નુકશાનનું કામ માત્ર શો-રૂમમાં કરાવવામાં આવશે."
                    },
                    {
                        num: "૦૩",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> નું વાહન માત્ર સગવડતાના ભાગરૂપે ઉપયોગમાં લેવાનું રહેશે, વાહન સાથે અથવા તે વાહનથી કોઈપણ ગેરકાયદેસરનું કૃત્ય કરવામાં આવશે અથવા કાયદાનો ભંગ કરશે તો કાયદેસરની કાર્યવાહી કરવામાં આવશે."
                    },
                    {
                        num: "૦૪",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> ના વાહન ચલાવતી વખતે થયેલ જાનહાની, મૃત્યુ કે અન્ય કોઈપણ શારીરિક, આર્થિક, માનસિક નુકસાનમાં (વાહન માલિક) ની કોઈપણ જવાબદારી રહેશે નહિ."
                    },
                    {
                        num: "૦૫",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> વાહન ચલાવતા પહેલા સંપૂર્ણ ચેક કરી લેવું. આપને લાગતી ખામીની અમો જાણ કરવાની રહેશે. વાહન પરત કરતી વખતે અમારા દ્વારા વાહન ચેક કરવામાં આવશે અને ખામી જણાય તો તેની કિંમત ભાડે લઈ જનારે ચુકવવાની રહેશે."
                    },
                    {
                        num: "૦૬",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> નું વાહન ચલાવતી વખતે કાયદાનું સંપૂર્ણ પાલન કરવાની તકેદારી રાખવી. અમો કાયદાના ભંગ સામે આપને કયારેય સાથ આપીશું નહિ. જેની સંપૂર્ણ જવાબદારી વાહન ભાડે લેનારની રહેશે."
                    },
                    {
                        num: "૦૭",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> વાહન ચલાવતી વખતે કોઈ પણ મુશ્કેલી જણાય કે અન્ય કોઈ મુશ્કેલી જણાય તો પ્રથમ અમોને જાણ કરવી."
                    },
                    {
                        num: "૦૮",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> ના વાહનની બુકિંગ રકમ પરત કરવામાં આવશે નહિ અને તારીખમાં ફેરફાર કરવામાં આવશે નહિ."
                    },
                    {
                        num: "૦૯",
                        text: "<strong>સૂર્ય દીપ કાર રેન્ટ, અમરેલી.</strong> કાયદેસર રીતે બિઝનેસ વેપારધંધો કરતી પ્રાઈવેટ સંસ્થા છે. સંસ્થા/કંપનીનાં નામ કે પ્રતિષ્ઠાને હાનિ પહોંચાડવા યુક્ત કૃત્ય ગુનો છે. તેની સામે કાયદેસરની કાર્યવાહી કરવામાં આવશે."
                    },
                    {
                        num: "૧૦",
                        text: "જો કોઈ કારણોસર વાહન પોલીસ દ્વારા અથવા કોઈપણ રીતે જપ્ત કરવામાં આવશે તો વાહન ભાડે રાખનારે <strong>રૂા.૧,૦૦,૦૦૦/- ડીપોઝીટ પેટે</strong> આપવાના રહેશે. જે તે સમયે વાહન છુટશે તે સમયે વાહનનું ભાડુ અને બીજા કોઈ ખર્ચ કાપીને વધેલી રકમ પરત કરવામાં આવશે. <em>(વાહન છોડાવવાની જવાબદારી વાહન ભાડે રાખનારની રહેશે.)</em>",
                        isWarn: true
                    },
                    {
                        num: "૧૧",
                        text: "અમો ઉપરના તમામ નીતિ-નિયમો શુદ્ધબુદ્ધિથી શાંતિથી સમજી, વિચારીને આ તમામમનું પાલન કરવાનું એ સહી કરીને લેખિત રૂપે બાંહેધરી આપીએ છીએ અને જાણતા ભુલ થાય તો અમારી જવાબદારી રહેશે. અમારી સામે કાયદેસરની કાર્યવાહી કરવાની (માલિક) ને લેખિતમાં છુટ આપીએ છીએ. તેમાં સહકાર આપીશું.",
                        isAgree: true
                    }
                ]
            }
        };

        var currentLang = 'en';
        var modalOverlay = document.getElementById('tcModalOverlay');
        var pointsContainer = document.getElementById('tcPointsContainer');
        var titleEl = document.getElementById('tcTitle');
        var subtitleEl = document.getElementById('tcSubtitle');
        var agreeLabelEl = document.getElementById('tcAgreeLabel');
        var agreeHintEl = document.getElementById('tcAgreeHint');
        var submitBtnTextEl = document.getElementById('tcSubmitBtnText');
        var cancelBtnEl = document.getElementById('tcCancelBtn');
        var agreeCheckbox = document.getElementById('tcAgreeCheckbox');
        var agreeCard = document.getElementById('tcAgreeCard');
        var submitBtn = document.getElementById('tcSubmitBtn');
        var langEnBtn = document.getElementById('tcLangEn');
        var langGuBtn = document.getElementById('tcLangGu');
        var closeBtn = document.getElementById('tcCloseBtn');
        var modalBody = document.getElementById('tcModalBody');
        var progressText = document.getElementById('tcProgressText');
        var progressFill = document.getElementById('tcProgressFill');
        var scrollCue = document.getElementById('tcScrollCue');
        var scrollCueText = document.getElementById('tcScrollCueText');

        var currentMode = 'url';
        var currentPayload = null;
        var hasScrolledToEnd = false;
        var totalPoints = 11;

        function renderPoints() {
            var data = tcData[currentLang];
            if (!data) return;

            if (titleEl) titleEl.textContent = data.title;
            if (subtitleEl) subtitleEl.textContent = data.subtitle;
            if (agreeLabelEl) agreeLabelEl.textContent = data.agreeLabel;
            if (agreeHintEl) agreeHintEl.textContent = data.agreeHint;
            if (scrollCueText) scrollCueText.textContent = data.scrollCue;
            if (submitBtnTextEl) submitBtnTextEl.textContent = data.btnContinue;
            if (cancelBtnEl) cancelBtnEl.textContent = data.btnCancel;

            var html = '';
            data.points.forEach(function(p, idx) {
                var extraClass = '';
                if (p.isWarn) extraClass = ' tc-point-warn';
                if (p.isAgree) extraClass = ' tc-point-agree';

                html += '<div class="tc-point-card' + extraClass + '" data-point-index="' + idx + '" style="animation-delay:' + Math.min(idx * 0.025, 0.25) + 's">' +
                    '<div class="tc-point-num">' + p.num + '</div>' +
                    '<div class="tc-point-text">' + p.text + '</div>' +
                    '</div>';
            });
            if (pointsContainer) pointsContainer.innerHTML = html;
            if (modalBody) modalBody.scrollTop = 0;
            updateProgress();
        }

        function updateProgress() {
            if (!modalBody) return;
            var scrollTop = modalBody.scrollTop;
            var scrollHeight = modalBody.scrollHeight - modalBody.clientHeight;
            var pct = scrollHeight > 0 ? Math.min(100, Math.round((scrollTop / scrollHeight) * 100)) : 100;
            if (progressFill) progressFill.style.width = pct + '%';

            // Estimate which clause is roughly in view for the progress label
            var cards = pointsContainer ? pointsContainer.querySelectorAll('.tc-point-card') : [];
            var currentIdx = 0;
            for (var i = 0; i < cards.length; i++) {
                if (cards[i].offsetTop - modalBody.offsetTop <= scrollTop + 80) {
                    currentIdx = i;
                }
            }
            var data = tcData[currentLang];
            if (progressText && data) {
                progressText.innerHTML = data.clauseWord + ' <b>' + (currentIdx + 1) + '</b> ' + data.ofWord + ' ' + totalPoints;
            }

            if (scrollHeight <= 4 || pct >= 96) {
                hasScrolledToEnd = true;
                if (scrollCue) scrollCue.classList.add('hidden');
                if (agreeCard) agreeCard.classList.remove('disabled');
            } else {
                if (scrollCue) scrollCue.classList.remove('hidden');
            }
        }

        if (modalBody) {
            modalBody.addEventListener('scroll', updateProgress);
        }

        window.switchTcLang = function(lang) {
            if (lang !== 'en' && lang !== 'gu') return;
            currentLang = lang;
            if (lang === 'en') {
                if (langEnBtn) langEnBtn.classList.add('active');
                if (langGuBtn) langGuBtn.classList.remove('active');
            } else {
                if (langGuBtn) langGuBtn.classList.add('active');
                if (langEnBtn) langEnBtn.classList.remove('active');
            }
            renderPoints();
        };

        function openTcModal(mode, payload) {
            currentMode = mode;
            currentPayload = payload;
            hasScrolledToEnd = false;
            if (agreeCheckbox) agreeCheckbox.checked = false;
            updateAgreeState();
            renderPoints();
            if (modalOverlay) {
                modalOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
            // Re-check scroll state shortly after open, in case content already fits without scrolling
            setTimeout(updateProgress, 50);
        }

        function closeTcModal() {
            if (modalOverlay) {
                modalOverlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        function updateAgreeState() {
            var isChecked = agreeCheckbox && agreeCheckbox.checked;
            if (agreeCard) {
                if (isChecked) {
                    agreeCard.classList.add('checked');
                } else {
                    agreeCard.classList.remove('checked');
                }
            }
            if (submitBtn) {
                if (isChecked) {
                    submitBtn.classList.add('active');
                } else {
                    submitBtn.classList.remove('active');
                }
            }
        }

        if (agreeCheckbox) {
            agreeCheckbox.addEventListener('change', function() {
                if (!hasScrolledToEnd) {
                    agreeCheckbox.checked = false;
                    var data = tcData[currentLang];
                    if (modalBody) modalBody.scrollTo({
                        top: modalBody.scrollHeight,
                        behavior: 'smooth'
                    });
                    if (data) alert(data.alertScroll);
                    return;
                }
                updateAgreeState();
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var data = tcData[currentLang];
                if (!agreeCheckbox || !agreeCheckbox.checked) {
                    alert(data ? data.alertAgree : 'Please agree to the Terms & Conditions before proceeding.');
                    return;
                }

                closeTcModal();

                if (currentMode === 'url' && currentPayload) {
                    window.location.href = currentPayload;
                } else if (currentMode === 'form' && currentPayload) {
                    window.tcAgreed = true;
                    currentPayload.submit();
                }
            });
        }

        if (closeBtn) closeBtn.addEventListener('click', closeTcModal);
        if (cancelBtnEl) cancelBtnEl.addEventListener('click', closeTcModal);

        if (modalOverlay) {
            modalOverlay.addEventListener('click', function(e) {
                if (e.target === modalOverlay) {
                    closeTcModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('open')) {
                closeTcModal();
            }
        });

        // Trigger on "Continue to Payment" button click
        var btnContinue = document.getElementById('btnContinueToPayment');
        if (btnContinue) {
            btnContinue.addEventListener('click', function(e) {
                e.preventDefault();
                var targetUrl = this.getAttribute('data-target-url') || '';
                openTcModal('url', targetUrl);
            });
        }

        // Trigger on "Upload Documents & Continue" form submission
        var docForm = document.getElementById('documentUploadForm');
        if (docForm) {
            docForm.addEventListener('submit', function(e) {
                if (!window.tcAgreed) {
                    e.preventDefault();
                    var files = docForm.querySelectorAll('input[type="file"][required]');
                    var allFilled = true;
                    files.forEach(function(inp) {
                        if (!inp.value) allFilled = false;
                    });
                    if (!allFilled) {
                        alert(currentLang === 'gu' ?
                            'કૃપા કરીને બંને જરૂરી દસ્તાવેજો અપલોડ કરો.' :
                            'Please upload both required documents.');
                        return;
                    }
                    openTcModal('form', docForm);
                }
            });
        }

        // Initial render (agree card starts disabled until scrolled, per-open reset handled in openTcModal)
        renderPoints();
    })();
</script>