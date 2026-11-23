let timer;

export function toast(message) {
    const el = document.querySelector('[data-toast]');
    if (!el) return;
    el.textContent = message;
    el.classList.add('is-shown');
    clearTimeout(timer);
    timer = setTimeout(() => el.classList.remove('is-shown'), 2600);
}
