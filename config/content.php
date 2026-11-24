<?php

/*
|--------------------------------------------------------------------------
| Page content
|--------------------------------------------------------------------------
| Copy taken from https://shubhanga.co.in (About, Features, For Parents &
| Students, Contact). Items marked SAMPLE are placeholders: the current site
| has no shipping / returns / privacy pages, so those are starter text to be
| reviewed by the client. Later all of this can come from the admin panel.
*/

return [

    'about' => [
        'intro' => 'At Shubhanga Publications, we believe education goes beyond textbooks: it is a journey guided by curiosity, clarity, and meaningful connections. Founded in 2024, we are a new-age academic publishing house dedicated to nurturing learners who pursue deep understanding, not just high scores.',
        'story' => 'Inspired by the vision of a passionate educator, Shubhanga Publications is driven by purpose, not profit. For us, publishing is not merely a business but a heartfelt service to the nation. Our mission is to make learning inclusive, engaging, and empowering for every child. Today, Shubhanga stands as a trusted partner to 1000+ schools and countless parents and students across India.',
        'vision' => 'To be a leader in educational innovation, transforming learning through NEP 2020-compliant resources. Our vision is to create environments where curiosity catalyses growth, enabling every student to reach their full potential and prepare for the challenges of the future.',
        'mission' => 'To empower educators and students with innovative NEP 2020-aligned resources that cultivate a joyful, outcome-driven learning environment. We are committed to developing tools that meet the evolving needs of the Indian education system, fostering a culture of academic excellence and holistic development.',
        'image' => '/images/site/about-1.webp',
        'gallery' => ['/images/site/for-schools.webp', '/images/site/about-2.webp', '/images/site/about-3.webp'],
        'timeline' => [
            ['year' => '2024', 'text' => 'Shubhanga Publications founded in Hyderabad by a passionate educator.'],
            ['year' => 'Year one', 'text' => '100+ titles published and adopted by schools across South India.'],
            ['year' => 'Today', 'text' => '560+ titles from Nursery to Grade 10, trusted by 1000+ schools.'],
        ],
        // "Why choose us?" from the home and features pages
        'values' => [
            ['title' => 'NEP & NCF aligned', 'text' => 'Books built to NEP 2020 and NCF 2023, from Nursery to Grade 10.'],
            ['title' => 'Authoritative & trusted', 'text' => 'Written by experienced educators and validated by school leaders.'],
            ['title' => 'Error-free content', 'text' => 'Every book is reviewed several times before it reaches a classroom.'],
            ['title' => 'Mind maps & keywords', 'text' => 'Every chapter ends with a mind map and highlighted keywords for quick revision.'],
            ['title' => 'Board exam ready', 'text' => 'Chapter-wise Q&A aligned with CBSE and State board patterns, with case-based, assertion-reasoning and HOTS questions.'],
            ['title' => 'Activity-based learning', 'text' => 'Games, puzzles, storytelling and drawing tasks that build foundational literacy and numeracy with joy.'],
        ],
    ],

    'faq' => [
        ['q' => 'Where can we buy Shubhanga books?', 'a' => 'Our books are available through partnered schools, trusted dealers and distributors, and select bookstores. Contact us to find your nearest distributor.'],
        ['q' => 'How do students use Shubhanga books effectively?', 'a' => 'Encourage your child to follow the worksheets, solve practice questions after each chapter, and use the mind maps before exams.'],
        ['q' => 'Are these books only for schools?', 'a' => 'While many schools officially adopt our books, parents can also request copies through schools or local dealers.'],
        ['q' => 'Do Shubhanga books help in competitive exams?', 'a' => 'Yes. Our higher classes include HOTS (Higher Order Thinking Skills) and Olympiad-based questions for advanced practice.'],
        ['q' => 'Which syllabuses do you cover?', 'a' => 'CBSE and the state boards of Karnataka, Andhra Pradesh, Telangana, Tamil Nadu, Kerala, Maharashtra, Odisha, West Bengal, Madhya Pradesh and Chhattisgarh, from Nursery to Grade 10.'],
        ['q' => 'Do you train teachers?', 'a' => 'Yes. We run orientation programs and teacher training workshops for partner schools, covering lesson design, classroom strategies and blended print and digital delivery.'],
        ['q' => 'How do teachers get a specimen copy?', 'a' => 'Fill in the specimen copy form with your school, board and grade, and our team will get in touch.'],
    ],

    // SAMPLE policies: the current site has none. Review with the client.
    'policies' => [
        'shipping' => [
            'title' => 'Shipping policy',
            'sections' => [
                ['heading' => 'Dispatch', 'text' => 'Orders are packed and dispatched from Hyderabad within 2 working days.'],
                ['heading' => 'Delivery time', 'text' => 'Most cities receive orders in 3 to 7 working days. School orders are delivered as agreed with our sales team.'],
                ['heading' => 'Charges', 'text' => 'Shipping is free on prepaid orders above ₹499. Smaller orders and cash on delivery orders carry a flat ₹49 charge.'],
            ],
        ],
        'returns' => [
            'title' => 'Returns and refunds',
            'sections' => [
                ['heading' => 'Eligible returns', 'text' => 'You can return a book within 7 days of delivery if it arrives damaged, misprinted, or is not the title you ordered.'],
                ['heading' => 'How to return', 'text' => 'Write to order@shubhanga.co.in with your order number and a photo of the book. We arrange a pickup or a replacement.'],
                ['heading' => 'Refunds', 'text' => 'Refunds go back to the original payment method within 7 working days of the returned book reaching us.'],
            ],
        ],
        'cancellation' => [
            'title' => 'Cancellation policy',
            'sections' => [
                ['heading' => 'Before dispatch', 'text' => 'You can cancel any order before it is dispatched, and prepaid orders are refunded in full.'],
                ['heading' => 'After dispatch', 'text' => 'Once an order has shipped it cannot be cancelled, but you can follow the returns process.'],
            ],
        ],
        'payment' => [
            'title' => 'Payment options',
            'sections' => [
                ['heading' => 'Online', 'text' => 'UPI, debit and credit cards, and net banking through our payment partner. Card details are never stored on our site.'],
                ['heading' => 'Cash on delivery', 'text' => 'Available on most pin codes.'],
                ['heading' => 'Schools and dealers', 'text' => 'Bank transfer against a proforma invoice. Credit terms are agreed with our sales team.'],
            ],
        ],
        'privacy' => [
            'title' => 'Privacy policy',
            'sections' => [
                ['heading' => 'What we collect', 'text' => 'Your name, contact details and delivery address when you order or fill in a form, and basic usage data to keep the site working.'],
                ['heading' => 'How we use it', 'text' => 'To deliver orders, reply to enquiries and, if you subscribe, send our newsletter. We never sell your data.'],
                ['heading' => 'Your choices', 'text' => 'You can ask us to update or delete your details at any time by writing to info@shubhanga.co.in.'],
            ],
        ],
        'terms' => [
            'title' => 'Terms of use',
            'sections' => [
                ['heading' => 'Using this site', 'text' => 'Content on this site, including book samples, is for personal and school use. Copying for resale is not allowed.'],
                ['heading' => 'Prices', 'text' => 'Prices and availability can change without notice. The price at checkout is the price you pay.'],
                ['heading' => 'Contact', 'text' => 'Questions about these terms can be sent through the contact page.'],
            ],
        ],
    ],

    'resources' => [
        ['title' => 'Full catalogue 2026–27', 'group' => 'Catalogue', 'meta' => 'Ask our team for the latest PDF', 'teachers' => false],
        ['title' => 'Smart Scores sample chapters', 'group' => 'Samples', 'meta' => 'CBSE Grades 6 to 10', 'teachers' => false],
        ['title' => 'Pre-primary programme overview', 'group' => 'Samples', 'meta' => 'Nursery, PP1, PP2', 'teachers' => false],
        ['title' => 'Teacher manuals and answer keys', 'group' => 'For teachers', 'meta' => 'Teachers only', 'teachers' => true],
        ['title' => 'Teacher training programme', 'group' => 'For teachers', 'meta' => 'Workshops for partner schools', 'teachers' => true],
    ],

    // Regional order desks from the contact page (shown on /bookshops).
    'bookshops' => [
        ['name' => 'Karnataka order desk', 'city' => 'Karnataka', 'state' => 'Karnataka', 'address' => 'Orders and dealer enquiries for Karnataka schools', 'phone' => '+91 78930 72424'],
        ['name' => 'Andhra Pradesh order desk', 'city' => 'Andhra Pradesh', 'state' => 'Andhra Pradesh', 'address' => 'Orders and dealer enquiries for Andhra Pradesh schools', 'phone' => '+91 78930 72424'],
        ['name' => 'Telangana order desk', 'city' => 'Hyderabad', 'state' => 'Telangana', 'address' => '3rd & 4th Floor, Balaji Empire, Jayabheri Enclave, Gachibowli', 'phone' => '+91 78930 72424'],
        ['name' => 'CBSE schools desk', 'city' => 'All India', 'state' => 'CBSE schools', 'address' => 'Orders for CBSE schools across India', 'phone' => '+91 78930 72424'],
        ['name' => 'Tamil Nadu order desk', 'city' => 'Tamil Nadu', 'state' => 'Tamil Nadu', 'address' => 'Orders and dealer enquiries for Tamil Nadu schools', 'phone' => '+91 78930 72424'],
        ['name' => 'Kerala order desk', 'city' => 'Kerala', 'state' => 'Kerala', 'address' => 'Orders and dealer enquiries for Kerala schools', 'phone' => '+91 78930 72424'],
        ['name' => 'Maharashtra order desk', 'city' => 'Maharashtra', 'state' => 'Maharashtra', 'address' => 'Orders and dealer enquiries for Maharashtra schools', 'phone' => '+91 78930 72424'],
        ['name' => 'Odisha order desk', 'city' => 'Odisha', 'state' => 'Odisha', 'address' => 'Orders and dealer enquiries for Odisha schools', 'phone' => '+91 78930 72424'],
    ],

    // Article teasers from the site's blog section; bodies are short summaries.
    'posts' => [
        [
            'slug' => 'revise-faster',
            'title' => '3 ways to revise faster',
            'date' => '',
            'tag' => 'Exam prep',
            'image' => '/images/site/blog-1.webp',
            'excerpt' => 'Revision is one of the most important steps in effective learning. With exams around the corner, the key is to revise smarter, not longer.',
            'body' => [
                'No matter how much you study, without revising, knowledge tends to fade over time. With exams around the corner, students often feel short of time.',
                'Revise in short, focused sessions, test yourself instead of re-reading, and use the mind map at the end of each chapter to see the whole topic at a glance.',
                'Here are practical ways to revise faster and retain more, so the time you have goes further.',
            ],
        ],
        [
            'slug' => 'mind-maps-for-exams',
            'title' => 'Exam hacks: how to use mind maps effectively',
            'date' => '',
            'tag' => 'Study skills',
            'image' => '/images/site/blog-2.webp',
            'excerpt' => 'A mind map helps you visualise information, organise ideas and connect concepts, so revising long chapters becomes easier and faster.',
            'body' => [
                'Preparing for exams can feel overwhelming when you have to revise lengthy chapters and remember endless details.',
                'A mind map puts the main idea in the centre and branches out into sub-topics, keywords and examples, which makes learning more natural.',
                'Every Shubhanga chapter ends with a mind map and highlighted keywords, so students can revise a whole chapter in minutes before an exam.',
            ],
        ],
        [
            'slug' => 'believe-in-yourself',
            'title' => 'Motivation for students: believe in yourself',
            'date' => '',
            'tag' => 'For students',
            'image' => '/images/site/blog-3.webp',
            'excerpt' => 'Every student faces moments of doubt. Believing in yourself is the first step toward success.',
            'body' => [
                'Whether it is before an exam, during a tough assignment, or while choosing a career path, it is easy to feel overwhelmed and question your own abilities.',
                'Break big goals into small steps, celebrate progress, and remember that mistakes are part of learning.',
                'Confidence grows with practice: regular worksheets and self-assessment help students see how far they have come.',
            ],
        ],
    ],
];
