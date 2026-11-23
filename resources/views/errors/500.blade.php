{{-- Standalone on purpose: if the app itself is failing, the main layout may fail too. --}}
<!DOCTYPE html>
<html lang="en-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Something went wrong</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #fff; color: #221518; font-family: system-ui, sans-serif; padding: 24px; }
        main { max-width: 520px; }
        b { display: block; font: 400 clamp(5rem, 16vw, 9rem)/.8 Georgia, serif; color: #c8102e; }
        h1 { font: 400 2rem/1.1 Georgia, serif; text-transform: uppercase; margin: 24px 0 12px; }
        p { color: #5a4d50; line-height: 1.7; }
        a { display: inline-block; margin-top: 16px; padding: 14px 26px; border-radius: 999px; background: #c8102e; color: #fff; text-decoration: none; font-size: .8rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; }
    </style>
</head>
<body>
    <main>
        <b>500</b>
        <h1>Something went wrong</h1>
        <p>We are fixing it. Please try again in a few minutes. If you were placing an order, check your email before trying again so you are not charged twice.</p>
        <a href="/">Back to home</a>
    </main>
</body>
</html>
