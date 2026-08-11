<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Contact Bridge routes contact forms from every website to the right inbox.">
    <title>Contact Bridge - Every message, routed.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #111827;
            --muted: #667085;
            --line: #dde3ec;
            --panel: #ffffff;
            --soft: #f4f7fb;
            --green: #16a36f;
            --blue: #305cf6;
            --coral: #ff6f61;
            --navy: #111a33;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background:
                linear-gradient(90deg, rgba(17, 26, 51, .045) 1px, transparent 1px),
                linear-gradient(180deg, rgba(17, 26, 51, .045) 1px, transparent 1px),
                linear-gradient(180deg, #f8fafc 0%, #eef3f8 100%);
            background-size: 44px 44px, 44px 44px, auto;
            font-family: "DM Sans", Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        .shell { width: min(1180px, calc(100% - 40px)); margin: auto; }
        .nav { height: 82px; display: flex; align-items: center; justify-content: space-between; }
        .logo { display: flex; align-items: center; gap: 12px; font: 800 17px Manrope, sans-serif; }
        .mark {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--navy);
            color: #fff;
            box-shadow: 0 12px 30px rgba(17, 26, 51, .18);
        }
        .mark:before { content: "CB"; font-size: 12px; letter-spacing: .08em; }
        .navlinks { display: flex; align-items: center; gap: 28px; color: #526076; font-size: 14px; }
        .navlinks a:hover { color: var(--ink); }
        .nav-cta, .primary {
            border: 0;
            border-radius: 8px;
            background: var(--navy);
            color: #fff;
            padding: 12px 18px;
            box-shadow: 0 14px 28px rgba(17, 26, 51, .2);
            font-weight: 700;
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.04fr) minmax(340px, .96fr);
            gap: 68px;
            align-items: center;
            padding: 78px 0 96px;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 7px 11px;
            color: #41506a;
            background: rgba(255,255,255,.72);
            font-size: 12px;
            font-weight: 700;
        }
        .dot { width: 7px; height: 7px; border-radius: 999px; background: var(--green); }
        h1 {
            max-width: 710px;
            margin: 22px 0 20px;
            font: 800 clamp(48px, 6vw, 82px)/.98 Manrope, sans-serif;
        }
        .accent { color: var(--blue); }
        .hero p { max-width: 570px; margin: 0; color: var(--muted); font-size: 18px; line-height: 1.65; }
        .hero-actions { display: flex; align-items: center; gap: 22px; margin-top: 31px; }
        .text-link { color: var(--navy); font-weight: 800; font-size: 14px; }
        .hero-note { margin-top: 20px; color: #7a8597; font-size: 12px; }

        .card {
            border: 1px solid var(--line);
            background: rgba(255,255,255,.88);
            border-radius: 8px;
            box-shadow: 0 28px 80px rgba(17, 26, 51, .14);
            backdrop-filter: blur(14px);
        }
        .form-card { padding: 24px; }
        .card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
        .card-title { font: 800 17px Manrope, sans-serif; }
        .lock { color: var(--green); font-size: 12px; font-weight: 700; }
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
        .field { margin-bottom: 14px; }
        .field label { display: block; color: #526076; font-size: 12px; margin: 0 0 7px; font-weight: 700; }
        .field input, .field select, .field textarea {
            width: 100%;
            border: 1px solid #d7deea;
            border-radius: 8px;
            outline: 0;
            background: #fff;
            color: var(--ink);
            padding: 12px 13px;
            font: 14px "DM Sans", Arial, sans-serif;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(48, 92, 246, .12);
        }
        .field textarea { min-height: 92px; resize: vertical; }
        .form-card button { width: 100%; cursor: pointer; margin-top: 5px; }
        .status { display: none; margin-top: 13px; padding: 11px; border-radius: 8px; font-size: 13px; }
        .status.show { display: block; }
        .status.success { background: rgba(22, 163, 111, .11); color: #08724b; }
        .status.error { background: rgba(255, 111, 97, .12); color: #bd3528; }
        .trust {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 18px;
            color: #6b778a;
            font-size: 11px;
        }
        .trust span { border-top: 1px solid var(--line); padding-top: 14px; }
        .trust strong { color: var(--ink); font-size: 13px; }

        .section { padding: 34px 0 104px; }
        .section-head { max-width: 610px; margin-bottom: 35px; }
        .kicker { color: var(--coral); font-size: 12px; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
        .section h2 { margin: 12px 0; font: 800 38px/1.08 Manrope, sans-serif; }
        .section-head p { color: var(--muted); line-height: 1.6; }
        .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .feature { padding: 24px; min-height: 198px; box-shadow: none; }
        .icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: #eef2ff;
            color: var(--blue);
            font-weight: 900;
        }
        .feature:nth-child(2) .icon { background: #ecfdf3; color: var(--green); }
        .feature:nth-child(3) .icon { background: #fff1ee; color: var(--coral); }
        .feature h3 { font: 800 17px Manrope, sans-serif; margin: 20px 0 9px; }
        .feature p { color: var(--muted); line-height: 1.55; font-size: 14px; margin: 0; }
        .footer {
            border-top: 1px solid var(--line);
            padding: 27px 0 35px;
            color: #6d7890;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }
        .footer span:first-child { color: var(--ink); font-weight: 800; }

        @media (max-width: 860px) {
            .hero { grid-template-columns: 1fr; gap: 52px; padding-top: 48px; }
            h1 { font-size: 58px; }
            .features { grid-template-columns: 1fr; }
            .navlinks a:not(.nav-cta) { display: none; }
        }
        @media (max-width: 520px) {
            .shell { width: min(100% - 28px, 1180px); }
            h1 { font-size: 46px; }
            .hero p { font-size: 16px; }
            .field-grid, .trust { grid-template-columns: 1fr; }
            .nav { height: 70px; }
            .hero-actions { align-items: flex-start; flex-direction: column; gap: 15px; }
            .form-card { padding: 19px; }
            .footer { gap: 15px; flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="shell">
    <nav class="nav">
        <a class="logo" href="{{ url('/') }}"><span class="mark"></span>Contact Bridge</a>
        <div class="navlinks">
            <a href="#how-it-works">How it works</a>
            <a href="#security">Security</a>
            <a href="{{ route('developers') }}">Developers</a>
            <a class="nav-cta" href="{{ route('admin.login') }}">Open dashboard</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div>
                <span class="eyebrow"><span class="dot"></span> Multi-site contact infrastructure</span>
                <h1>Every message. <span class="accent">Right inbox.</span></h1>
                <p>One reliable bridge for contact forms across your websites. Capture the source, route to the right team, and reply from the visitor's original email.</p>
                <div class="hero-actions">
                    <a class="primary" href="#demo">Send a test message</a>
                    <a class="text-link" href="#how-it-works">See how it works -></a>
                </div>
                <div class="hero-note">Built for teams that care where every lead comes from.</div>
            </div>

            <div class="demo" id="demo">
                <form class="card form-card" id="contact-form">
                    <div class="card-top">
                        <div class="card-title">Send a test message</div>
                        <div class="lock">SMTP secured</div>
                    </div>
                    <div class="field-grid">
                        <div class="field">
                            <label for="firstName">First name</label>
                            <input id="firstName" name="firstName" placeholder="Adeleke" required>
                        </div>
                        <div class="field">
                            <label for="lastName">Last name</label>
                            <input id="lastName" name="lastName" placeholder="Igwe" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="email">Your email</label>
                        <input id="email" name="email" type="email" placeholder="you@company.com" required>
                    </div>
                    <div class="field">
                        <label for="recipient">Registered recipient</label>
                        <input id="recipient" name="recipient" type="email" placeholder="sales@company.com" required>
                    </div>
                    <div class="field">
                        <label for="product">Product of interest</label>
                        <select id="product" name="product">
                            <option value="">Select a product</option>
                            <option>BothSign</option>
                            <option>MoniERP</option>
                            <option>Sqotes</option>
                            <option>General inquiry</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Tell us what you need..." required></textarea>
                    </div>
                    <button class="primary" type="submit">Send message</button>
                    <div class="status" id="status"></div>
                    <div class="trust">
                        <span><strong>Reply-to ready</strong><br>Respond directly to the sender</span>
                        <span><strong>Origin tracked</strong><br>Know every source</span>
                    </div>
                </form>
            </div>
        </section>

        <section class="section" id="how-it-works">
            <div class="section-head">
                <span class="kicker">One control plane</span>
                <h2>Your websites stay separate. Your workflow stays simple.</h2>
                <p>Contact Bridge turns scattered forms into a single, observable delivery pipeline for your team.</p>
            </div>
            <div class="features">
                <div class="card feature">
                    <div class="icon">1</div>
                    <h3>Route with precision</h3>
                    <p>Each registered website is paired with its own recipient, so every message lands with the right team automatically.</p>
                </div>
                <div class="card feature" id="security">
                    <div class="icon">2</div>
                    <h3>Trust every origin</h3>
                    <p>Only active origins in your database can submit. Unregistered and disabled sites are rejected before delivery.</p>
                </div>
                <div class="card feature">
                    <div class="icon">3</div>
                    <h3>See what happened</h3>
                    <p>Track delivery status, failures, sender details, and source context from a focused admin workspace.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <span>Contact Bridge {{ date('Y') }}</span>
        <span>Built for clarity at scale.</span>
    </footer>
</div>

<script>
document.getElementById('contact-form').addEventListener('submit', async function (event) {
    event.preventDefault();

    const form = event.currentTarget;
    const status = document.getElementById('status');
    const button = form.querySelector('button');

    status.className = 'status';
    status.textContent = 'Sending...';
    status.classList.add('show');
    button.disabled = true;

    try {
        const response = await fetch('{{ url('/api/contact') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(new FormData(form)))
        });
        const data = await response.json();

        if (! response.ok) {
            throw new Error(data.message || 'Unable to send your message.');
        }

        status.className = 'status success show';
        status.textContent = data.message;
        form.reset();
    } catch (error) {
        status.className = 'status error show';
        status.textContent = error.message;
    } finally {
        button.disabled = false;
    }
});
</script>
</body>
</html>
