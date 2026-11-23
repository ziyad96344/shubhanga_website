import { initSmoothScroll } from './modules/smooth-scroll';
import { initHeader } from './modules/header';
import { initCart } from './modules/cart';
import { initCarousel } from './modules/carousel';
import { initTabs } from './modules/tabs';
import { initAnimations } from './modules/animations';
import { runPreloader } from './modules/preloader';
import { initCatalogue } from './modules/catalogue';
import { initShopFinder } from './modules/shops';
import { initCheckout } from './modules/checkout';
import { initSearch } from './modules/search';

document.addEventListener('DOMContentLoaded', () => {
    const ready = runPreloader();
    const lenis = initSmoothScroll();
    initHeader(lenis);
    initCart(lenis);
    initCarousel();
    initTabs();
    initCatalogue(lenis);
    initShopFinder();
    initCheckout();
    initSearch();
    initAnimations(ready);
});
