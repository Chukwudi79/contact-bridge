<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Contact Bridge API documentation for connecting website contact forms.">
    <title>Developer docs - Contact Bridge</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#111827; --muted:#667085; --line:#dde3ec; --panel:#fff; --soft:#f4f7fb; --blue:#305cf6; --green:#16a36f; --navy:#111a33; --coral:#ff6f61; }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--ink); background:linear-gradient(90deg,rgba(17,26,51,.045) 1px,transparent 1px),linear-gradient(180deg,rgba(17,26,51,.045) 1px,transparent 1px),linear-gradient(180deg,#f8fafc 0%,#eef3f8 100%); background-size:44px 44px,44px 44px,auto; font-family:"DM Sans",Arial,sans-serif; }
        a { color:inherit; text-decoration:none; }
        .shell { width:min(1120px,calc(100% - 40px)); margin:auto; }
        .nav { height:82px; display:flex; align-items:center; justify-content:space-between; }
        .logo { display:flex; align-items:center; gap:12px; font:800 17px Manrope,sans-serif; }
        .mark { width:36px; height:36px; display:grid; place-items:center; border-radius:8px; background:var(--navy); color:#fff; box-shadow:0 12px 30px rgba(17,26,51,.18); }
        .mark:before { content:"CB"; font-size:12px; letter-spacing:.08em; }
        .navlinks { display:flex; align-items:center; gap:24px; color:#526076; font-size:14px; }
        .navlinks a:hover { color:var(--ink); }
        .nav-cta { border-radius:8px; background:var(--navy); color:#fff; padding:12px 18px; font-weight:700; box-shadow:0 14px 28px rgba(17,26,51,.2); }
        .hero { padding:72px 0 48px; max-width:790px; }
        .eyebrow { display:inline-flex; gap:8px; align-items:center; border:1px solid var(--line); border-radius:999px; padding:7px 11px; color:#41506a; background:rgba(255,255,255,.72); font-size:12px; font-weight:700; }
        .dot { width:7px; height:7px; border-radius:999px; background:var(--green); }
        h1 { margin:20px 0 16px; font:800 clamp(44px,6vw,72px)/1 Manrope,sans-serif; letter-spacing:-.04em; }
        .accent { color:var(--blue); }
        .hero p { max-width:680px; color:var(--muted); font-size:18px; line-height:1.65; margin:0; }
        .layout { display:grid; grid-template-columns:230px minmax(0,1fr); gap:34px; padding-bottom:90px; }
        .toc { position:sticky; top:24px; align-self:start; border:1px solid var(--line); border-radius:8px; background:rgba(255,255,255,.72); padding:18px; }
        .toc-label { color:var(--coral); font-size:11px; font-weight:800; letter-spacing:.13em; text-transform:uppercase; margin-bottom:12px; }
        .toc a { display:block; color:#526076; font-size:13px; padding:8px 0; }
        .toc a:hover { color:var(--blue); }
        .section { margin-bottom:22px; padding:30px; border:1px solid var(--line); border-radius:8px; background:rgba(255,255,255,.9); box-shadow:0 20px 60px rgba(17,26,51,.07); }
        h2 { margin:0 0 12px; font:800 25px Manrope,sans-serif; }
        h3 { margin:26px 0 10px; font:800 16px Manrope,sans-serif; }
        .section p, .section li { color:var(--muted); line-height:1.65; font-size:14px; }
        .section ul { padding-left:20px; }
        .endpoint { display:flex; align-items:center; gap:10px; margin:18px 0; font-family:monospace; font-size:14px; }
        .method { border-radius:5px; padding:6px 8px; background:#ecfdf3; color:#08724b; font:800 11px "DM Sans",sans-serif; }
        pre { overflow:auto; margin:14px 0 0; padding:18px; border-radius:8px; background:var(--navy); color:#dce7ff; font:13px/1.65 Consolas,monospace; }
        code { font-family:Consolas,monospace; }
        .inline-code { padding:3px 6px; border-radius:4px; background:#eef2ff; color:#2548bc; }
        .table-wrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th,td { padding:12px 10px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
        th { color:#526076; font-size:11px; text-transform:uppercase; letter-spacing:.08em; }
        td { color:var(--muted); }
        td:first-child { color:var(--ink); font-family:Consolas,monospace; }
        .note { margin-top:16px; padding:13px 15px; border-left:3px solid var(--blue); background:#eef2ff; color:#374b90; font-size:13px; line-height:1.55; }
        .footer { border-top:1px solid var(--line); padding:27px 0 35px; color:#6d7890; font-size:12px; display:flex; justify-content:space-between; }
        .footer span:first-child { color:var(--ink); font-weight:800; }
        @media(max-width:760px) { .shell{width:min(100% - 28px,1120px)} .navlinks a:not(.nav-cta){display:none} .layout{grid-template-columns:1fr} .toc{position:static} .section{padding:22px} .footer{gap:15px; flex-direction:column} }
    </style>
</head>
<body>
<div class="shell">
    <nav class="nav">
        <a class="logo" href="{{ url('/') }}"><span class="mark"></span>Contact Bridge</a>
        <div class="navlinks">
            <a href="{{ url('/') }}">Back to home</a>
            <a class="nav-cta" href="{{ route('admin.login') }}">Open dashboard</a>
        </div>
    </nav>

    <header class="hero">
        <span class="eyebrow"><span class="dot"></span> Developer documentation</span>
        <h1>Ship contact forms in <span class="accent">minutes.</span></h1>
        <p>Connect any approved website to Contact Bridge. Send one JSON request, and we will validate the origin, save the submission, route it through SMTP, and set the visitor as the email reply-to.</p>
    </header>

    <div class="layout">
        <aside class="toc">
            <div class="toc-label">On this page</div>
            <a href="#quickstart">Quick start</a>
            <a href="#payload">Payload</a>
            <a href="#responses">Responses</a>
            <a href="#browser">Browser example</a>
            <a href="#security">Origin security</a>
        </aside>

        <main>
            <section class="section" id="quickstart">
                <h2>Quick start</h2>
                <p>Before sending requests, an administrator must add your website origin and recipient email in the dashboard under <strong>Sources</strong>. The origin must match the browser <code class="inline-code">Origin</code> header exactly.</p>
                <div class="endpoint"><span class="method">POST</span><code>{{ url('/api/contact') }}</code></div>
                <div class="note">Requests from localhost, preview URLs, or unregistered domains are rejected until they are explicitly added as an active source.</div>
            </section>

            <section class="section" id="payload">
                <h2>Request payload</h2>
                <p>Send JSON with the fields below. The <code class="inline-code">recipient</code> is supplied by the initiating website and must match the recipient registered for that origin.</p>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Field</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td>firstName</td><td>string</td><td>Yes</td><td>Visitor's first name.</td></tr>
                            <tr><td>lastName</td><td>string</td><td>Yes</td><td>Visitor's last name.</td></tr>
                            <tr><td>email</td><td>email</td><td>Yes</td><td>Visitor email. Used as Reply-To.</td></tr>
                            <tr><td>product</td><td>string</td><td>No</td><td>Product or topic of interest.</td></tr>
                            <tr><td>message</td><td>string</td><td>Yes</td><td>The visitor's message.</td></tr>
                            <tr><td>recipient</td><td>email</td><td>Yes</td><td>Registered inbox for this origin.</td></tr>
                        </tbody>
                    </table>
                </div>
                <h3>Sample JSON</h3>
                <pre><code>{
  "firstName": "Adeleke",
  "lastName": "Igwe",
  "email": "adeleke@example.com",
  "product": "BothSign",
  "message": "I would like to learn more about your product.",
  "recipient": "sales@company.com"
}</code></pre>
            </section>

            <section class="section" id="responses">
                <h2>Test with curl</h2>
                <p>Replace the URL, origin, and recipient with values registered by your administrator.</p>
                <pre><code>curl -X POST "{{ url('/api/contact') }}" \
  -H "Origin: https://platform-a.example" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "firstName": "Adeleke",
    "lastName": "Igwe",
    "email": "adeleke@example.com",
    "product": "BothSign",
    "message": "Please contact me about a demo.",
    "recipient": "sales@company.com"
  }'</code></pre>
                <h3>Success response: 202 Accepted</h3>
                <pre><code>{
  "message": "Your message has been sent successfully.",
  "submission_id": 42,
  "status": "sent"
}</code></pre>
                <h3>Common error responses</h3>
                <ul>
                    <li><strong>403</strong> - the request origin is not registered or inactive.</li>
                    <li><strong>422</strong> - the payload is invalid or the recipient does not match the registered source.</li>
                    <li><strong>429</strong> - the source exceeded the configured rate limit.</li>
                    <li><strong>503</strong> - the submission was saved, but SMTP delivery failed and was logged as failed.</li>
                </ul>
            </section>

            <section class="section" id="browser">
                <h2>Browser integration</h2>
                <p>Use this from the contact form hosted on your approved website. The browser automatically supplies the origin header.</p>
                <pre><code>const response = await fetch('{{ url('/api/contact') }}', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    firstName: form.firstName.value,
    lastName: form.lastName.value,
    email: form.email.value,
    product: form.product.value,
    message: form.message.value,
    recipient: 'sales@company.com'
  })
});

const result = await response.json();</code></pre>
            </section>

            <section class="section" id="security">
                <h2>Origin security</h2>
                <p>Contact Bridge does not accept an allowlist from environment variables. Each website and its recipient are managed in the database through the admin dashboard.</p>
                <ul>
                    <li>Only active, registered origins can submit.</li>
                    <li>The payload recipient must match the database recipient for that origin.</li>
                    <li>Every request is tracked with pending, sent, failed, in-progress, or resolved status.</li>
                    <li>Successful email delivery uses the visitor email as <code class="inline-code">Reply-To</code>.</li>
                </ul>
            </section>
        </main>
    </div>

    <footer class="footer"><span>Contact Bridge {{ date('Y') }}</span><span>Build once. Route everywhere.</span></footer>
</div>
</body>
</html>
