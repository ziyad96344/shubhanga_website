import { toast } from './toast';

/**
 * Cart drawer backed by the Laravel session cart (/cart/items endpoints).
 * The server owns prices; this module only renders what it returns.
 */
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

const rupees = (n) => '₹' + Number(n).toLocaleString('en-IN');

const escape = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

async function api(method, url, body) {
    const res = await fetch(url, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: body ? JSON.stringify(body) : undefined,
    });
    if (!res.ok) throw new Error(`Cart request failed (${res.status})`);
    return res.json();
}

export function initCart(lenis) {
    const drawer = document.querySelector('[data-cart-drawer]');
    if (!drawer) return;

    const backdrop = document.querySelector('[data-cart-backdrop]');
    const list = drawer.querySelector('[data-cart-items]');
    const foot = drawer.querySelector('[data-cart-foot]');
    const total = drawer.querySelector('[data-cart-total]');
    const shipText = drawer.querySelector('[data-ship-text]');
    const shipBar = drawer.querySelector('[data-ship-bar]');
    const counts = document.querySelectorAll('[data-cart-count]');
    const opener = document.querySelector('[data-cart-open]');
    const base = drawer.dataset.endpoint; // /cart/items

    let cart = window.__cart || { items: [], count: 0, subtotal: 0, free_from: 499 };

    const render = () => {
        counts.forEach((c) => {
            c.textContent = cart.count;
            c.classList.toggle('has-items', cart.count > 0);
        });
        total.textContent = rupees(cart.subtotal);
        foot.hidden = cart.items.length === 0;

        const left = cart.free_from - cart.subtotal;
        shipText.textContent = left > 0
            ? `Add ${rupees(left)} more for free shipping on prepaid orders`
            : 'Free shipping on prepaid orders';
        shipBar.style.width = Math.min(100, (cart.subtotal / cart.free_from) * 100) + '%';

        if (!cart.items.length) {
            list.innerHTML = '<div class="drawer-empty"><strong>Your cart is empty</strong>Add books from any class to see them here.</div>';
            return;
        }

        list.innerHTML = cart.items.map((i) => `
            <div class="cart-item">
                <a href="${escape(i.url)}" class="cover"><img src="${escape(i.image)}" alt=""></a>
                <div>
                    <h4><a href="${escape(i.url)}">${escape(i.name)}</a></h4>
                    <p>${escape(i.meta)}</p>
                    <div class="qty" role="group" aria-label="Quantity for ${escape(i.name)}">
                        <button type="button" data-set="${i.qty - 1}" data-id="${escape(i.id)}" aria-label="Remove one">−</button>
                        <span>${i.qty}</span>
                        <button type="button" data-set="${i.qty + 1}" data-id="${escape(i.id)}" aria-label="Add one">+</button>
                    </div>
                </div>
                <div class="line-price">${rupees(i.line_total)}</div>
            </div>`).join('');
    };

    let lastFocus;
    const setOpen = (open) => {
        drawer.classList.toggle('is-open', open);
        backdrop.classList.toggle('is-open', open);
        drawer.setAttribute('aria-hidden', String(!open));
        open ? lenis?.stop() : lenis?.start();
        if (open) {
            lastFocus = document.activeElement;
            drawer.querySelector('[data-cart-close]').focus();
        } else {
            lastFocus?.focus();
        }
    };

    opener?.addEventListener('click', () => setOpen(true));
    document.querySelectorAll('[data-cart-close]').forEach((el) => el.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
    });

    // Quantity buttons inside the drawer
    list.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-set]');
        if (!btn) return;
        btn.disabled = true;
        try {
            cart = await api('PATCH', `${base}/${encodeURIComponent(btn.dataset.id)}`, { qty: Number(btn.dataset.set) });
            render();
            document.dispatchEvent(new CustomEvent('cart:changed', { detail: cart }));
        } catch {
            toast('Could not update the cart. Please try again.');
            btn.disabled = false;
        }
    });

    // Any "Add to cart" button on the site
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-add-to-cart]');
        if (!btn) return;

        const qty = Math.max(1, Number(btn.dataset.qty) || 1);
        const label = btn.textContent;
        btn.disabled = true;

        try {
            cart = await api('POST', base, { slug: btn.dataset.id, qty });
            render();
            btn.classList.add('is-added');
            btn.textContent = 'Added to cart';
            setTimeout(() => {
                btn.classList.remove('is-added');
                btn.textContent = label.trim();
            }, 1600);
            counts.forEach((c) => {
                c.classList.remove('bump');
                void c.offsetWidth;
                c.classList.add('bump');
            });
            toast(`${btn.dataset.name} added to your cart`);
        } catch {
            toast('Could not add that book. Please try again.');
        } finally {
            btn.disabled = false;
        }
    });

    // The cart page changes the cart too; keep the header count in sync.
    document.addEventListener('cart:changed', (e) => {
        cart = e.detail;
        render();
    });

    render();
}

export { api as cartApi, rupees };
