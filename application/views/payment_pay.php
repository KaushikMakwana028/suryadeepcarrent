<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$existing_request = !empty($existing_request) ? $existing_request : array();
$payment_settings = !empty($payment_settings) ? $payment_settings : array();
$cancel_url       = isset($cancel_url) ? $cancel_url : base_url('dashboard');
$back_url         = isset($back_url) ? $back_url : base_url('documents?booking_id=' . (int) $booking['id'] . '&customer_id=' . (int) $booking['customer_id']);
$total_amount     = isset($total_amount) ? (float) $total_amount : (float) $booking['amount'];
$advance_due      = isset($advance_due) && (float) $advance_due > 0
    ? (float) $advance_due
    : ((isset($booking['advance_amount']) && (float) $booking['advance_amount'] > 0) ? (float) $booking['advance_amount'] : 1000.00);
if ($total_amount > 0 && $advance_due > $total_amount) {
    $advance_due = $total_amount;
}
$payable_amount   = isset($payable_amount) ? (float) $payable_amount : $advance_due;
$balance_amount   = max(0, $total_amount - $payable_amount);
$current_step     = isset($current_step) ? (int) $current_step : 3;
$razorpay_cfg     = !empty($razorpay_cfg) ? $razorpay_cfg : array();
$razorpay_key_id  = !empty($razorpay_cfg['razorpay_key_id']) ? $razorpay_cfg['razorpay_key_id'] : 'rzp_live_TdoPPos3deJpcW';
$company_name     = !empty($razorpay_cfg['razorpay_company_name']) ? $razorpay_cfg['razorpay_company_name'] : 'SURYA DEEP CAR RENT';
$theme_color      = !empty($razorpay_cfg['razorpay_theme_color']) ? $razorpay_cfg['razorpay_theme_color'] : '#2563eb';
$currency         = !empty($razorpay_cfg['razorpay_currency']) ? $razorpay_cfg['razorpay_currency'] : 'INR';
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

    /* ── Split grid ── */
    .split-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 860px) {
        .split-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── Payment method selection cards ── */
    .pay-methods-wrap {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 18px;
    }

    .pay-method-card {
        border: 2px solid rgba(35, 94, 167, .14);
        border-radius: 14px;
        padding: 18px 20px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .pay-method-card:hover {
        border-color: var(--accent);
        box-shadow: 0 6px 18px rgba(35, 94, 167, .08);
    }

    .pay-method-card.selected {
        border-color: var(--accent);
        background: #fbfdff;
        box-shadow: 0 8px 24px rgba(35, 94, 167, .12);
    }

    .pay-method-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .pay-method-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .pay-method-radio {
        width: 20px;
        height: 20px;
        accent-color: var(--accent);
        cursor: pointer;
    }

    .pay-method-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .icon-cash {
        background: #eef8f2;
        color: #1a8a4a;
    }

    .icon-online {
        background: #eff6ff;
        color: #2563eb;
    }

    .icon-bank {
        background: #fef9e7;
        color: #d97706;
    }

    .pay-method-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
    }

    .pay-method-desc {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
        line-height: 1.4;
    }

    .pay-method-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .badge-cash {
        background: #eef8f2;
        color: #1a8a4a;
    }

    .badge-online {
        background: #eff6ff;
        color: #2563eb;
    }

    .badge-bank {
        background: #fef9e7;
        color: #b45309;
    }

    .pay-method-body {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed rgba(35, 94, 167, .12);
        display: none;
    }

    .pay-method-card.selected .pay-method-body {
        display: block;
    }

    .pay-chips {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .pay-chip {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        border: 0.5px solid #cbd5e1;
    }

    .btn-pay-rzp {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform .15s ease, box-shadow .15s ease;
        box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
    }

    .btn-pay-rzp:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, .35);
    }

    .btn-pay-cash {
        background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%);
        color: #ffffff !important;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 16px rgba(13, 148, 136, .25);
    }

    .btn-pay-cash:hover {
        transform: translateY(-1px);
    }

    /* ── Summary & details ── */
    .summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        padding: 6px 0;
        color: #475569;
    }

    .summary-row strong {
        color: var(--ink);
    }

    .summary-row.total {
        border-top: 1.5px solid #cbd5e1;
        padding-top: 10px;
        margin-top: 6px;
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
    }

    .summary-row.total strong {
        color: #1e3a8a;
        font-size: 18px;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid rgba(35, 94, 167, .09);
    }

    .booking-actions .btn,
    .booking-actions .btn-secondary {
        min-width: 148px;
    }

    @media (max-width: 480px) {
        .booking-actions {
            flex-direction: column;
        }

        .booking-actions .btn,
        .booking-actions .btn-secondary {
            width: 100%;
            min-width: unset;
            text-align: center;
        }
    }

    /* Bank receipt dropzone */
    .pay-file-zone {
        position: relative;
        border: 2px dashed rgba(35, 94, 167, .22);
        border-radius: 12px;
        background: rgba(246, 250, 255, .7);
        padding: 16px 14px;
        text-align: center;
        cursor: pointer;
        margin-top: 8px;
    }

    .pay-file-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .loading-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(3px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-direction: column;
        gap: 12px;
        font-weight: 700;
        font-size: 15px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- ── Stepper ── -->
<section class="section-card step-shell">
    <div class="stepper">
        <?php
        $steps = array(1 => 'Booking', 2 => 'Document', 3 => 'Payment');
        foreach ($steps as $step_no => $step_label):
            $is_active = $current_step === $step_no;
            $is_done   = $current_step > $step_no;
        ?>
            <div class="step-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $is_done ? 'done' : ''; ?>">
                <div class="step-badge"><?php echo $is_done ? '&#10003;' : $step_no; ?></div>
                <div class="step-label"><?php echo html_escape($step_label); ?></div>
            </div>
            <?php if ($step_no < 3): ?>
                <div class="step-line <?php echo $current_step > $step_no ? 'done' : ''; ?>"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- ── Main Split Grid ── -->
<div class="split-grid">

    <!-- LEFT: Payment Options -->
    <section class="section-card">
        <div class="card-head">
            <div>
                <div class="eyebrow">Step 3 of 3</div>
                <h3>Choose Payment Method</h3>
                <p>Select your preferred mode of payment to confirm your booking.</p>
            </div>
        </div>

        <div class="pay-methods-wrap">

            <!-- ── Option 1: Cash ── -->
            <div class="pay-method-card selected" id="card_cash" onclick="selectPaymentMethod('cash')">
                <div class="pay-method-head">
                    <div class="pay-method-left">
                        <input type="radio" name="payment_choice" id="choice_cash" value="cash" checked class="pay-method-radio">
                        <div class="pay-method-icon icon-cash">💵</div>
                        <div>
                            <div class="pay-method-title">Cash on Pickup</div>
                            <div class="pay-method-desc">Pay the booking amount in cash when you collect the car or upon delivery.</div>
                        </div>
                    </div>
                    <span class="pay-method-badge badge-cash">Pay Later</span>
                </div>

                <div class="pay-method-body" id="body_cash">
                    <div style="background:#f8fafc; border-radius:10px; padding:12px 16px; font-size:13px; color:#334155; line-height:1.5;">
                        ℹ️ <strong>No advance payment required right now.</strong> You can pay the total fare of ₹<?php echo number_format($total_amount, 2); ?> in cash directly at our desk or to the driver upon vehicle pickup.
                    </div>

                    <form method="post" action="<?php echo base_url('payments/confirm_cash'); ?>" style="margin-top:16px;">
                        <input type="hidden" name="booking_id" value="<?php echo (int) $booking['id']; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo (int) $booking['customer_id']; ?>">
                        <button type="submit" class="btn-pay-cash">
                            ✓ Confirm Booking with Cash
                        </button>
                    </form>
                </div>
            </div>

            <!-- ── Option 2: Online Payment via Razorpay ── -->
            <div class="pay-method-card" id="card_online" onclick="selectPaymentMethod('online')">
                <div class="pay-method-head">
                    <div class="pay-method-left">
                        <input type="radio" name="payment_choice" id="choice_online" value="online" class="pay-method-radio">
                        <div class="pay-method-icon icon-online">⚡</div>
                        <div>
                            <div class="pay-method-title">Pay Online (Razorpay)</div>
                            <div class="pay-method-desc">Instant confirmation via UPI, Cards, NetBanking, and Wallets.</div>
                        </div>
                    </div>
                    <span class="pay-method-badge badge-online">Instant</span>
                </div>

                <div class="pay-method-body" id="body_online">
                    <div class="pay-chips">
                        <span class="pay-chip">Google Pay</span>
                        <span class="pay-chip">PhonePe</span>
                        <span class="pay-chip">Paytm UPI</span>
                        <span class="pay-chip">Credit / Debit Card</span>
                        <span class="pay-chip">Net Banking</span>
                        <span class="pay-chip">Wallets</span>
                    </div>

                    <div style="margin-top:14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 14px; font-size:13px; color:#1e40af;">
                        🔒 <strong>Secure 256-bit encrypted checkout.</strong> Pay advance amount of <strong>₹<?php echo number_format($payable_amount, 2); ?></strong> now via Razorpay.<?php if ($balance_amount > 0): ?> Remaining balance of <strong>₹<?php echo number_format($balance_amount, 2); ?></strong> will be payable upon vehicle pickup.<?php endif; ?>
                    </div>

                    <div style="margin-top:16px;">
                        <button type="button" class="btn-pay-rzp" id="btn_trigger_razorpay">
                            💳 Pay Advance Online ₹<?php echo number_format($payable_amount, 2); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <a class="btn-secondary" href="<?php echo html_escape($back_url); ?>">&#8592; Previous Step</a>
            <a class="btn-secondary js-swal-confirm" href="<?php echo html_escape($cancel_url); ?>" data-swal-title="Cancel booking?" data-swal-text="This incomplete booking draft will be removed." data-swal-confirm="Yes, cancel">Cancel</a>
        </div>
    </section>

    <!-- RIGHT: Booking Summary -->
    <aside class="section-card accent-card">
        <div class="eyebrow">Summary</div>
        <div class="card-head">
            <div>
                <h3>Reservation Details</h3>
                <p><?php echo html_escape($booking['booking_code']); ?></p>
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-row">
                <span>Vehicle:</span>
                <strong><?php echo html_escape($booking['vehicle_name']); ?></strong>
            </div>
            <?php if (!empty($booking['registration_no'])): ?>
            <div class="summary-row">
                <span>Reg No:</span>
                <strong><?php echo html_escape($booking['registration_no']); ?></strong>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span>Trip Schedule:</span>
                <strong><?php echo html_escape($booking['trip_label']); ?></strong>
            </div>
            <div class="summary-row">
                <span>Route:</span>
                <strong><?php echo html_escape($booking['trip_route']); ?></strong>
            </div>
            <div class="summary-row">
                <span>Duration / Mode:</span>
                <strong><?php echo html_escape($booking['trip_mode_label']); ?></strong>
            </div>
            <div class="summary-row total">
                <span>Total Fare:</span>
                <strong>₹<?php echo number_format($total_amount, 2); ?></strong>
            </div>
            <div class="summary-row" style="color:#2563eb;font-weight:700;">
                <span>Advance Payable Now:</span>
                <strong>₹<?php echo number_format($payable_amount, 2); ?></strong>
            </div>
            <?php if ($balance_amount > 0): ?>
            <div class="summary-row" style="color:#059669;font-weight:700;">
                <span>Balance on Pickup:</span>
                <strong>₹<?php echo number_format($balance_amount, 2); ?></strong>
            </div>
            <?php endif; ?>
        </div>

        <div class="info-grid" style="grid-template-columns:1fr;">
            <div class="feature-card">
                <strong>Customer</strong>
                <span><?php echo html_escape($booking['customer_name']); ?> &bull; <?php echo html_escape($booking['customer_phone']); ?></span>
            </div>
            <div class="feature-card">
                <strong>Instant Support</strong>
                <span>Need help with your booking or payment? Call our 24x7 support desk.</span>
            </div>
        </div>
    </aside>
</div>

<!-- Loading overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
    <div id="loadingText">Processing payment...</div>
</div>

<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
function selectPaymentMethod(mode) {
    var cards = ['cash', 'online'];
    cards.forEach(function(m) {
        var card = document.getElementById('card_' + m);
        var radio = document.getElementById('choice_' + m);
        if (card && radio) {
            if (m === mode) {
                card.classList.add('selected');
                radio.checked = true;
            } else {
                card.classList.remove('selected');
                radio.checked = false;
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var rzpBtn = document.getElementById('btn_trigger_razorpay');
    var loadingOverlay = document.getElementById('loadingOverlay');
    var loadingText = document.getElementById('loadingText');

    if (!rzpBtn) return;

    rzpBtn.addEventListener('click', function(e) {
        e.preventDefault();
        rzpBtn.disabled = true;
        if (loadingOverlay) {
            loadingText.textContent = 'Initializing secure payment...';
            loadingOverlay.style.display = 'flex';
        }

        // Step 1: Request order from server
        var formData = new FormData();
        formData.append('booking_id', '<?php echo (int) $booking['id']; ?>');
        formData.append('customer_id', '<?php echo (int) $booking['customer_id']; ?>');

        fetch('<?php echo base_url('payments/create_razorpay_order'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            rzpBtn.disabled = false;

            if (!data.success) {
                alert(data.message || 'Unable to start payment. Please try again or select Cash.');
                return;
            }

            // Step 2: Open Razorpay Checkout modal
            var options = {
                "key": data.key_id,
                "amount": data.amount,
                "currency": data.currency,
                "name": data.company_name,
                "description": data.description,
                "image": data.logo_url,
                "order_id": data.order_id,
                "handler": function (response) {
                    // Step 3: Verify payment on server
                    if (loadingOverlay) {
                        loadingText.textContent = 'Verifying payment with bank...';
                        loadingOverlay.style.display = 'flex';
                    }

                    var verifyData = new FormData();
                    verifyData.append('booking_id', '<?php echo (int) $booking['id']; ?>');
                    verifyData.append('customer_id', '<?php echo (int) $booking['customer_id']; ?>');
                    verifyData.append('razorpay_payment_id', response.razorpay_payment_id || '');
                    verifyData.append('razorpay_order_id', response.razorpay_order_id || '');
                    verifyData.append('razorpay_signature', response.razorpay_signature || '');

                    fetch('<?php echo base_url('payments/verify_razorpay'); ?>', {
                        method: 'POST',
                        body: verifyData
                    })
                    .then(function(vRes) { return vRes.json(); })
                    .then(function(vData) {
                        if (loadingOverlay) loadingOverlay.style.display = 'none';
                        if (vData.success) {
                            window.location.href = vData.redirect || '<?php echo base_url('dashboard'); ?>';
                        } else {
                            alert(vData.message || 'Payment verification failed. Please contact support.');
                        }
                    })
                    .catch(function(err) {
                        if (loadingOverlay) loadingOverlay.style.display = 'none';
                        alert('Network error during verification. Please contact support with Payment ID: ' + response.razorpay_payment_id);
                    });
                },
                "prefill": {
                    "name": data.customer_name,
                    "contact": data.customer_phone,
                    "email": data.customer_email
                },
                "theme": {
                    "color": data.theme_color
                },
                "modal": {
                    "ondismiss": function() {
                        rzpBtn.disabled = false;
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response) {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                alert('Payment failed: ' + (response.error.description || 'Unknown error'));
            });
            rzp.open();
        })
        .catch(function(err) {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            rzpBtn.disabled = false;
            alert('Failed to connect to payment server. Please try again or select Cash.');
        });
    });
});
</script>
