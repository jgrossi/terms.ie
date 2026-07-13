<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — terms.ie</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --fg: #18181b;
            --muted: #71717a;
            --bg: #fafafa;
            --border: #e4e4e7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; flex-direction: column;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            color: var(--fg); background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }
        .nav { padding: 18px 24px; border-bottom: 1px solid var(--border); background: #fff; }
        .brand { font-weight: 700; font-size: 18px; letter-spacing: -.02em; text-decoration: none; color: var(--fg); }
        .brand span { color: var(--primary); }
        main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { max-width: 460px; text-align: center; }
        .code { font-size: 12px; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: var(--primary); margin: 0; }
        h1 { font-size: 28px; letter-spacing: -.02em; margin: 12px 0 8px; }
        .msg { color: var(--muted); line-height: 1.6; margin: 0 0 28px; }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary); color: #fff; text-decoration: none;
            font-weight: 500; font-size: 14px; padding: 10px 18px; border-radius: 8px;
        }
        .btn:hover { background: #1d4ed8; }
        footer { padding: 20px 24px; text-align: center; color: var(--muted); font-size: 13px; }
    </style>
</head>
<body>
    <nav class="nav">
        <a href="/" class="brand">terms<span>.ie</span></a>
    </nav>
    <main>
        <div class="card">
            <p class="code">@yield('code')</p>
            <h1>@yield('heading')</h1>
            <p class="msg">@yield('message')</p>
            <a href="/" class="btn">← Back to terms.ie</a>
        </div>
    </main>
    <footer>Made in Ireland 🇮🇪</footer>
</body>
</html>
