import { toast } from './toast';

const escape = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

/**
 * Header search panel with live book suggestions, and the footer
 * newsletter form (submitted in the background with a toast).
 */
export function initSearch() {
    const panel = document.querySelector('[data-search]');
    const opener = document.querySelector('[data-search-open]');
    if (panel && opener) {
        const input = panel.querySelector('[data-search-input]');
        const results = panel.querySelector('[data-search-results]');
        const url = input.dataset.suggestUrl;
        let timer;
        let controller;

        const setOpen = (open) => {
            panel.hidden = !open;
            opener.setAttribute('aria-expanded', String(open));
            if (open) input.focus();
            else opener.focus();
        };

        opener.addEventListener('click', () => setOpen(panel.hidden));
        panel.querySelector('[data-search-close]').addEventListener('click', () => setOpen(false));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !panel.hidden) setOpen(false);
            if (e.key === '/' && panel.hidden && !e.target.closest('input, textarea, select')) {
                e.preventDefault();
                setOpen(true);
            }
        });

        input.addEventListener('input', () => {
            clearTimeout(timer);
            const q = input.value.trim();
            if (q.length < 2) {
                results.innerHTML = '';
                return;
            }
            timer = setTimeout(async () => {
                controller?.abort();
                controller = new AbortController();
                try {
                    const res = await fetch(`${url}?q=${encodeURIComponent(q)}`, { headers: { Accept: 'application/json' }, signal: controller.signal });
                    const books = await res.json();
                    results.innerHTML = books.length
                        ? books.map((b) => `
                            <li><a href="${escape(b.url)}">
                                <img src="${escape(b.image)}" alt="">
                                <span><strong>${escape(b.name)}</strong><small>${escape(b.meta)}</small></span>
                                <em>₹${Number(b.price).toLocaleString('en-IN')}</em>
                            </a></li>`).join('') + `<li class="search-all"><a href="${escape(panel.querySelector('form').action)}?q=${encodeURIComponent(q)}">See all results for “${escape(q)}”</a></li>`
                        : `<li class="search-empty">No books match “${escape(q)}”. Try a subject or class.</li>`;
                } catch (err) {
                    if (err.name !== 'AbortError') results.innerHTML = '';
                }
            }, 180);
        });
    }

    const newsletter = document.querySelector('[data-newsletter]');
    newsletter?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = newsletter.querySelector('button');
        btn.disabled = true;
        try {
            const res = await fetch(newsletter.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(newsletter),
            });
            const data = await res.json();
            if (res.ok) {
                newsletter.reset();
                toast(data.message);
            } else {
                toast(data.errors?.email?.[0] || 'Please check your email address.');
            }
        } catch {
            toast('Could not subscribe right now. Please try again.');
        } finally {
            btn.disabled = false;
        }
    });
}
