<?php

/*
|--------------------------------------------------------------------------
| Website forms
|--------------------------------------------------------------------------
| Each form is defined once here: the page copy, the fields and their rules.
| resources/views/forms/show.blade.php renders it and EnquiryController
| validates and stores it in the `enquiries` table.
|
| Field keys: label, type (text|email|tel|number|select|textarea|file),
| rules (Laravel validation), options (select), wide (full row), hint.
| `name`, `email` and `phone` are copied into their own columns.
*/

$classes = ['Nursery', 'PP1', 'PP2', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
$boards = ['CBSE', 'Karnataka State Board', 'Andhra Pradesh State Board', 'Telangana State Board', 'Other'];
$states = ['Andhra Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Odisha', 'Punjab', 'Rajasthan', 'Tamil Nadu', 'Telangana', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Other'];

return [

    'specimen-copy' => [
        'title' => 'Request a <em>specimen copy</em>',
        'eyebrow' => 'For teachers',
        'intro' => 'Tell us your school, board, grade and subject, and our regional team will arrange inspection copies for your teachers.',
        'submit' => 'Request my copy',
        'success' => 'Thank you. Your specimen copy request is in, and our team will get in touch by phone or email.',
        'aside' => [
            'heading' => 'What you get',
            'points' => ['Inspection copies for your grade and subject', 'Orientation programs for teachers', 'A call from your regional representative'],
        ],
        'fields' => [
            'name' => ['label' => 'Your name', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'designation' => ['label' => 'Designation', 'type' => 'select', 'options' => ['Teacher', 'Head of department', 'Coordinator', 'Principal'], 'rules' => 'required'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:160'],
            'phone' => ['label' => 'Mobile number', 'type' => 'tel', 'rules' => 'required|string|min:10|max:15'],
            'school' => ['label' => 'School name', 'type' => 'text', 'rules' => 'required|string|max:160', 'wide' => true],
            'board' => ['label' => 'Board', 'type' => 'select', 'options' => $boards, 'rules' => 'required'],
            'class' => ['label' => 'Class', 'type' => 'select', 'options' => $classes, 'rules' => 'required'],
            'subject' => ['label' => 'Subject or title', 'type' => 'text', 'rules' => 'required|string|max:160', 'wide' => true, 'hint' => 'For example: Smart Scores Science, Grade 10'],
            'address' => ['label' => 'School address', 'type' => 'textarea', 'rules' => 'required|string|max:500', 'wide' => true],
        ],
    ],

    'bulk-order' => [
        'title' => 'School <em>bulk order</em>',
        'eyebrow' => 'For schools',
        'intro' => 'Ordering for a whole class or section? Send us your book list and we will reply with a quote, school discount and delivery date.',
        'submit' => 'Get a quote',
        'success' => 'Thank you. We have your book list and will send a quote within 2 working days.',
        'aside' => [
            'heading' => 'School orders include',
            'points' => ['School pricing on 560+ titles', 'Syllabus-mapped books for your board', 'Teacher training and orientation programs'],
        ],
        'fields' => [
            'name' => ['label' => 'Contact person', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'school' => ['label' => 'School name', 'type' => 'text', 'rules' => 'required|string|max:160'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:160'],
            'phone' => ['label' => 'Mobile number', 'type' => 'tel', 'rules' => 'required|string|min:10|max:15'],
            'city' => ['label' => 'City', 'type' => 'text', 'rules' => 'required|string|max:80'],
            'students' => ['label' => 'Approx. number of students', 'type' => 'number', 'rules' => 'required|integer|min:1|max:20000'],
            'books' => ['label' => 'Book list', 'type' => 'textarea', 'rules' => 'required|string|max:3000', 'wide' => true, 'hint' => 'Title, class and quantity, one per line'],
        ],
    ],

    'become-a-distributor' => [
        'title' => 'Become a <em>distributor</em>',
        'eyebrow' => 'For booksellers',
        'intro' => 'Stock our books in your shop or territory. Tell us about your business and our sales team will share margins, credit terms and the territory map.',
        'submit' => 'Send application',
        'success' => 'Thank you. Our sales team will contact you within 3 working days.',
        'aside' => [
            'heading' => 'Why stock our list',
            'points' => ['560+ titles from Nursery to Grade 10', 'Structured distribution with clear orders', 'Timely supply and transparent dealer support'],
        ],
        'fields' => [
            'name' => ['label' => 'Your name', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'business' => ['label' => 'Business name', 'type' => 'text', 'rules' => 'required|string|max:160'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:160'],
            'phone' => ['label' => 'Mobile number', 'type' => 'tel', 'rules' => 'required|string|min:10|max:15'],
            'city' => ['label' => 'City', 'type' => 'text', 'rules' => 'required|string|max:80'],
            'state' => ['label' => 'State', 'type' => 'select', 'options' => $states, 'rules' => 'required'],
            'gst' => ['label' => 'GST number', 'type' => 'text', 'rules' => 'nullable|string|max:20', 'hint' => 'Optional'],
            'experience' => ['label' => 'Years in book trade', 'type' => 'number', 'rules' => 'nullable|integer|min:0|max:80'],
            'message' => ['label' => 'Tell us about your business', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000', 'wide' => true],
        ],
    ],

    'write-for-us' => [
        'title' => 'Write <em>for us</em>',
        'eyebrow' => 'Authors and editors',
        'intro' => 'We work with teachers and subject experts on new titles and revisions. Share your proposal and a short sample, and our editorial team will get back to you.',
        'submit' => 'Submit proposal',
        'success' => 'Thank you. Our editorial team reviews every proposal and replies within 3 weeks.',
        'aside' => [
            'heading' => 'We look for',
            'points' => ['Classroom teaching experience', 'A clear idea for a class and subject', 'A sample chapter or outline'],
        ],
        'fields' => [
            'name' => ['label' => 'Your name', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:160'],
            'phone' => ['label' => 'Mobile number', 'type' => 'tel', 'rules' => 'required|string|min:10|max:15'],
            'role' => ['label' => 'You are', 'type' => 'select', 'options' => ['Author', 'Editor', 'Illustrator', 'Reviewer'], 'rules' => 'required'],
            'subject' => ['label' => 'Subject', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'class' => ['label' => 'Class', 'type' => 'select', 'options' => $classes, 'rules' => 'required'],
            'proposal' => ['label' => 'Your proposal', 'type' => 'textarea', 'rules' => 'required|string|max:3000', 'wide' => true],
            'sample' => ['label' => 'Sample chapter or CV', 'type' => 'file', 'rules' => 'nullable|file|mimes:pdf,doc,docx|max:5120', 'wide' => true, 'hint' => 'PDF or Word, up to 5 MB'],
        ],
    ],

    'contact' => [
        'title' => 'Contact <em>us</em>',
        'eyebrow' => 'Get in touch',
        'intro' => 'Questions about an order, a book or a school supply? Write to us and the right team will reply.',
        'submit' => 'Send message',
        'success' => 'Thank you. We have your message and will reply within 1 working day.',
        'contact_card' => true,
        'fields' => [
            'name' => ['label' => 'Your name', 'type' => 'text', 'rules' => 'required|string|max:120'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:160'],
            'phone' => ['label' => 'Mobile number', 'type' => 'tel', 'rules' => 'nullable|string|min:10|max:15', 'hint' => 'Optional'],
            'topic' => ['label' => 'Topic', 'type' => 'select', 'options' => ['Order or delivery', 'A book', 'School supply', 'Dealership', 'Something else'], 'rules' => 'required'],
            'message' => ['label' => 'Message', 'type' => 'textarea', 'rules' => 'required|string|max:3000', 'wide' => true],
        ],
    ],
];
