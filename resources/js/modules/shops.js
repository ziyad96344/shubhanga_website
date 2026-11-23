/**
 * Bookshop finder: filters the list by search text and state, client side.
 */
export function initShopFinder() {
    const root = document.querySelector('[data-shop-finder]');
    if (!root) return;

    const search = root.querySelector('[data-shop-search]');
    const state = root.querySelector('[data-shop-state]');
    const cards = [...root.querySelectorAll('[data-shop]')];
    const count = root.querySelector('[data-shop-count]');
    const empty = root.querySelector('[data-shop-empty]');

    const apply = () => {
        const q = search.value.trim().toLowerCase();
        const s = state.value;
        let shown = 0;
        cards.forEach((card) => {
            const match = (!q || card.dataset.text.includes(q)) && (!s || card.dataset.state === s);
            card.hidden = !match;
            if (match) shown += 1;
        });
        count.textContent = shown;
        empty.hidden = shown > 0;
    };

    search.addEventListener('input', apply);
    state.addEventListener('change', apply);
}
