/**
 * Checkout: keep the summary's shipping and total in step with the chosen
 * payment method (cash on delivery carries a delivery charge). The server
 * recalculates everything on submit; this is display only.
 */
export function initCheckout() {
    const form = document.querySelector('[data-checkout]');
    if (!form) return;

    const summary = form.querySelector('[data-summary]');
    const shippingEl = summary.querySelector('[data-summary-shipping]');
    const totalEl = summary.querySelector('[data-summary-total]');
    const subtotal = Number(summary.dataset.subtotal);
    const fee = { cod: Number(form.dataset.codShipping), online: Number(form.dataset.onlineShipping) };
    const rupees = (n) => '₹' + n.toLocaleString('en-IN');

    const update = () => {
        const method = form.querySelector('[name="payment_method"]:checked')?.value || 'cod';
        const shipping = fee[method] ?? 0;
        shippingEl.textContent = shipping ? rupees(shipping) : 'Free';
        totalEl.textContent = rupees(subtotal + shipping);
    };

    form.addEventListener('change', (e) => {
        if (e.target.name === 'payment_method') update();
    });

    // Stop double orders from a double click.
    form.addEventListener('submit', () => {
        const btn = form.querySelector('.checkout-submit');
        btn.disabled = true;
        btn.firstChild.textContent = 'Placing order… ';
    });

    update();
}
