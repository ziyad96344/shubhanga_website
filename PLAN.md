# School Books Publisher Website — Plan

## Goal

School books publisher ke liye brand + catalogue website. Content structure **Oswaal Books (oswaalbooks.com) jaisa** rahega: boards, classes, exams, book types, product cards, free resources, specimen copy, dealer network.

Lekin look Oswaal jaisa Shopify template nahi hoga. Design modern, editorial aur premium hoga, scroll-triggered keyframe animations ke saath. Generic "AI template" jaisa bilkul nahi lagna chahiye.

E-commerce poora dikhega (cart, price, discount), par backend halka rahega kyunki online sales kam expected hain.

## Audience

1. **Students aur parents** — class/board ke hisaab se books dhundhna aur kharidna.
2. **Teachers** — specimen copy, free resources, solutions.
3. **Schools / coaching centres** — book adoption, bulk order form.
4. **Dealers / distributors** — dealership enquiry, catalogue, bookshop locator.

## Content structure (Oswaal reference se)

### Categories

- **Boards:** CBSE, CISCE (ICSE / ISC), State boards (client ke hisaab se), NCERT solutions, Olympiad
- **Classes:** Early learning (Nursery–KG), Class 1–12, har class ka alag collection
- **Exams (agar client karta hai):** JEE, NEET, CUET, CTET etc.
- **Book types:** Textbooks, Question Banks, Sample Papers, Workbooks, Worksheets, Lab Manuals, Mind Maps
- **Early learning range** (Oswaal ka "Lil Legends" jaisa, apna naam): writing, colouring, activity, story, phonics books
- **Sets / combos:** "Set of 4", poori class ka bundle

### Product card fields

- Cover image (hover pe 3D tilt + back cover)
- Badge: New / Best Seller / Sale
- Title (short title + chhota subtitle; Oswaal jaisa lamba SEO title nahi, woh product page ke meta mein jaayega)
- Class, Board, Subject chips
- Rating + review count
- Sale price, MRP cut, % off
- Add to cart, Buy now

### Product detail page

- Image gallery (front, back, sample pages), "Look inside" flipbook
- Price, MRP, discount, offers (free shipping above ₹499 etc.)
- Class, board, subject, edition year, ISBN, pages, language
- Key features list, table of contents
- Free resource download (solutions PDF)
- Ratings & reviews
- "Request specimen copy" (teachers ke liye)
- Related books / same series / frequently bought together

### Category (collection) page

- Filters: Board, Class, Subject, Book type, Price, Year
- Sort: Bestselling, New, Price
- Grid / list toggle
- Banner per category with animated heading

## Tech stack

Local machine pe already hai: WAMP (PHP 8.4, MySQL 8.4 / MariaDB 11.4), Composer 2, Node 24.

| Part | Choice | Kyun |
|---|---|---|
| Backend | Laravel (latest) on PHP 8.4 | Client ki choice, custom admin ke liye best |
| Database | MySQL 8.4 (WAMP) | Already chal raha hai |
| Frontend | Pure Laravel Blade + vanilla JS (Vite) | Server-rendered HTML, SEO apne aap achha, koi Node server nahi chahiye |
| SEO | Blade server-side render | Google ko poora HTML milta hai |
| Styling | Tailwind CSS + custom design tokens | Apna design system, template look se bachne ke liye |
| Animation | GSAP + ScrollTrigger + SplitText | Scroll animation, timelines, pinning |
| Smooth scroll | Lenis | Premium smooth scroll |
| Micro-interactions | CSS keyframes + vanilla JS | Hover, cart drawer, menus |
| Admin panel | User ka existing admin panel code, baad mein wire hoga | Pehle frontend banega |
| Auth | Laravel session auth, alag `admin` guard; customers ke liye OTP / email login | Admin aur customer login alag |
| Images | Intervention Image (resize + WebP), `storage/app/public` | Covers fast load hon |
| Payment | Razorpay PHP SDK (UPI, cards) + COD option + WhatsApp order | Client ki preference pe |
| Mail | Laravel Mail (SMTP) | Order confirmation, form submissions admin ko |
| Queue | Database queue | Emails / image processing background mein |

Hosting note: Blade hone se normal shared hosting (cPanel) pe bhi chalega. Assets `npm run build` se pehle hi compile ho jaate hain.

## Custom admin panel (`/admin`)

Design public site jaisa hi saaf aur modern, par animations halki (speed zaroori hai).

### Modules

| Module | Kya hoga |
|---|---|
| Dashboard | Aaj / mahine ke orders, revenue, naye enquiries, low stock, top books (charts) |
| Books (Products) | Add / edit / delete, multiple images, sample pages, MRP, sale price, stock, ISBN, edition, pages, SEO fields, badges (New / Best Seller), active / draft |
| Categories | Boards, Classes, Subjects, Book types, Exams — alag alag manage, order (drag to sort) |
| Series | Series bana ke books uske andar |
| Sets / Combos | Multiple books ka bundle, combo price |
| Orders | List, filter by status, order detail, status update (Pending → Packed → Shipped → Delivered), invoice PDF, tracking number |
| Customers | List, unke orders, addresses |
| Coupons | % / flat discount, min order, expiry, usage limit |
| Reviews | Approve / reject |
| Specimen requests | Teachers ki requests, status (New / Sent / Rejected), export CSV |
| Bulk order form | Schools ke orders |
| Dealer enquiries | Dealership applications |
| Author enquiries | "Be an Author" submissions, manuscript file download |
| Contact messages | Contact form inbox |
| Free resources | PDFs upload, class / subject wise |
| Bookshop locator | Dealers / shops: state, city, address, phone, map link |
| Homepage content | Hero slides, stats numbers, topper stories, awards, featured series, announcement bar |
| Blog / News / Media | Posts with editor, cover image, SEO |
| Product videos | YouTube links |
| Pages | About, FAQ, Policies (rich text editor) |
| Catalogue | PDF upload |
| Settings | Logo, contact numbers, email, address, social links, shipping charges, free-shipping limit, COD on/off, Razorpay keys |
| Admin users | Multiple admins, roles (Super admin, Order manager, Content editor) |

### Main database tables

`admins`, `users`, `addresses`, `boards`, `classes`, `subjects`, `book_types`, `exams`, `series`, `books`, `book_images`, `book_class` / `book_subject` (pivots), `bundles`, `bundle_items`, `carts`, `cart_items`, `orders`, `order_items`, `payments`, `coupons`, `reviews`, `specimen_requests`, `bulk_orders`, `dealer_enquiries`, `author_enquiries`, `contact_messages`, `resources`, `bookshops`, `hero_slides`, `testimonials`, `awards`, `stats`, `posts`, `videos`, `pages`, `settings`.

## Pages (sitemap)

1. **Home**
2. **Shop / Collections:** board-wise, class-wise, book-type-wise, early learning, exams
3. **Product detail page**
4. **Cart drawer + Cart page + Checkout**
5. **Login / Account** (orders, addresses) — phase 2
6. **Free Resources:** solutions download, mind maps, sample papers, mock tests
7. **Request Specimen Copy** (teachers)
8. **Order Form** (schools / bulk)
9. **Become a Dealer / Distributor**
10. **Bookshop Locator** (state / city filter)
11. **Be an Author / Editor**
12. **Catalogue** (PDF download)
13. **About Us:** story, timeline, team, awards
14. **Media / News / Blog**
15. **Product Videos**
16. **Contact Us**
17. **FAQ**
18. **Policies:** Privacy, Shipping, Terms, Return & Refund, Cancellation, Payment options

## Header aur navigation

- **Top announcement bar:** free shipping above ₹499, COD available (text slide/marquee animation)
- **Header:** logo, search (live results with cover thumbnails), login, cart with count
- **Main menu:** Early Learning · School (mega menu: Board → Class → Book type) · Exams · Free Resources · Specimen Copy · Blog
- **Mega menu:** columns stagger se open hote hain, featured book image side mein
- Scroll down pe header chhota hota hai, scroll up pe wapas aata hai

## Home page sections (order + animation)

| # | Section | Content | Animation |
|---|---|---|---|
| 1 | Hero | Badi headline + latest books (Oswaal ke banner carousel ki jagah) | Load pe covers fan ki tarah khulte hain. Scroll pe stack bikharke pinned transition |
| 2 | Book finder | "Apni books 10 second mein dhundho": Board + Class dropdown + Go | Card neeche se slide-up, dropdown change pe matching covers live shuffle |
| 3 | Stats | Teachers, schools, parents, students ke numbers | Counters 0 se count up, numbers ke peeche halki line draw |
| 4 | Latest releases | 8 product cards carousel + View all | Cards stagger reveal, drag-to-scroll carousel |
| 5 | Shop by board / class | Board tiles + Class 1–12 journey | **Horizontal pinned scroll**: Nursery → 12 slide hote hain, har class ka color badalta hai |
| 6 | Trending books | Tabs: Sample Papers / Question Banks / Olympiad / Early Learning | Tab switch pe cards flip-out / flip-in |
| 7 | Featured series | 2–3 flagship series | Book 3D mein ghoomti hai, features line by line reveal (SplitText mask) |
| 8 | Look inside | Sample pages | Scroll pe book khulti hai (clip-path / page flip) |
| 9 | Early learning range | Colourful kids books | Playful: doodles draw hote hain (SVG stroke animation), books bounce in |
| 10 | Topper stories | Student photo, score badge, quote | Sticky stacking cards, score number count up |
| 11 | Awards & recognition | Award images / logos | Infinite marquee, hover pe slow |
| 12 | Free resources | Solutions, mind maps, sample papers | Parallax layers + download icons ka micro animation |
| 13 | For teachers & dealers | Specimen copy, dealership, bookshop locator — 3 CTA blocks | Background color sweep + magnetic buttons |
| 14 | Blog / news | Latest 3 posts | Image mask reveal |
| 15 | Contact strip | Address, customer care (students / teachers / dealers), hours | Simple fade |
| 16 | Footer | Know Us, Explore, Categories, Policies, Social, payment icons | Badi brand wordmark neeche se reveal |

## Design direction — "AI template" jaisa na lage

**Avoid karna hai:**
- Purple/blue gradients, glassmorphism cards
- Inter font har jagah, centered hero with "Empowering the future of education"
- Emoji / generic icon packs, 3 equal cards ki row bar bar
- Stock photos of smiling kids
- Har cheez pe same fade-up animation
- Oswaal jaisa crowded, banner-heavy Shopify look

**Karna hai:**
- **Typography-led**: strong display serif (Fraunces / Instrument Serif) + clean grotesk body (General Sans / Satoshi)
- **Asymmetric editorial grid**, magazine jaisa layout
- **Real assets**: asli book covers ki achhi photography, printing press / office photos
- **Brand palette**: deep ink blue + paper cream + ek bright accent (mustard / coral). Har board / class ka apna color code
- **Paper texture, grain, hand-drawn doodles** (sparingly)
- **Animation variety**: pin, horizontal, mask reveal, 3D, parallax, counter, SVG draw — har section alag
- Content Oswaal jitna rich, par **zyada white space** aur saaf hierarchy

## Performance aur accessibility

- `prefers-reduced-motion` pe animations band ya simple
- Images Next/Image, WebP/AVIF, lazy load
- Mobile pe heavy pinned sections simplify (horizontal scroll ko swipe carousel banana)
- Lighthouse target: Performance 90+
- SEO: har product / collection ka meta, structured data (Product, Book, BreadcrumbList), sitemap

## Client se chahiye (content checklist)

- [ ] Publisher ka naam, logo, brand colors
- [ ] Kaunse boards / classes / exams cover karte hain
- [ ] Books ki list: title, class, board, subject, type, MRP, sale price, ISBN, pages, edition year
- [ ] Book covers high quality (front + back) aur sample pages
- [ ] Stats (schools, teachers, students, dealers, districts)
- [ ] Topper / teacher testimonials (photos ke saath, permission ke saath)
- [ ] Awards ki images
- [ ] Free resources (solutions PDFs etc.)
- [ ] Dealer / bookshop list
- [ ] Catalogue PDF
- [ ] Company story, founding year, team photos
- [ ] Policies text (shipping, return, privacy)
- [ ] Payment preference: Razorpay ya WhatsApp order; COD chahiye ya nahi
- [ ] Customer care numbers aur email

## Build phases

1. **Phase 1 — Setup:** Laravel project in `D:\websites\publication`, Inertia + Vue + Tailwind + GSAP + Lenis, MySQL database (WAMP), virtual host
2. **Phase 2 — Database:** migrations, models, relationships, sample data seeders (fake books, boards, classes)
3. **Phase 3 — Design system:** fonts, colors, tokens, components (button, product card, chips, section header, mega menu)
4. **Phase 4 — Home page** with all animations (database ke sample data se)
5. **Phase 5 — Admin panel core:** admin login, layout, dashboard, Books, Categories, Series, Homepage content, Settings
6. **Phase 6 — Collection page** (filters) + **Product detail page**
7. **Phase 7 — Customer login, cart, checkout** (Razorpay + COD + WhatsApp), admin Orders, Coupons, Customers
8. **Phase 8 — Forms:** specimen copy, bulk order, dealer, author, contact + unke admin inbox; Free Resources; Bookshop Locator
9. **Phase 9 — About, Blog/Media, Videos, FAQ, Contact, Policies** + unke admin modules; admin roles
10. **Phase 10 — Polish:** mobile, performance, SSR + SEO (sitemap, structured data), deploy
