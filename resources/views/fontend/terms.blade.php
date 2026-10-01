<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Terms & Conditions - Chewr</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-color: #ff9f1c;
            --brand-color-hover: #e88e17;
            --text-main: #334155;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .header .container {
            display: flex;
            align-items: center;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--brand-color);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .page-header {
            text-align: center;
            padding: 4rem 0 2rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 1rem;
            letter-spacing: -1px;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .content-card {
            background-color: var(--bg-card);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            margin-bottom: 4rem;
            border: 1px solid var(--border-color);
        }

        .content-body {
            font-size: 1rem;
        }

        /* Default styling for generated HTML content */
        .content-body h1, .content-body h2, .content-body h3, .content-body h4 {
            color: #0f172a;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .content-body h1 { font-size: 2rem; }
        .content-body h2 { font-size: 1.5rem; }
        .content-body h3 { font-size: 1.25rem; }

        .content-body p {
            margin-bottom: 1.25rem;
            color: var(--text-main);
        }

        .content-body a {
            color: var(--brand-color);
            text-decoration: none;
            font-weight: 500;
        }

        .content-body a:hover {
            text-decoration: underline;
            color: var(--brand-color-hover);
        }

        .content-body ul, .content-body ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }

        .content-body li {
            margin-bottom: 0.5rem;
        }

        .footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            background-color: var(--bg-card);
            font-size: 0.875rem;
        }

        .footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            .content-card {
                padding: 1.5rem;
            }
            .page-header {
                padding: 3rem 0 1.5rem;
            }
        }
    </style>
</head>
<body>

    {{-- <header class="header">
        <div class="container">
            <a href="/" class="brand-logo">Chewr</a>
        </div>
    </header> --}}

    <main class="container">
        <div class="page-header">
            <h1 class="page-title">Terms & Conditions</h1>
            <p class="page-subtitle">Please read these terms and conditions carefully before using our service.</p>
        </div>

        <div class="content-card">
            <div class="content-body">
                @if(isset($terms) && $terms->page_content)
                    {!! $terms->page_content !!}
                @else
                    <p><i>The terms and conditions have not been published yet. Check back soon!</i></p>
                @endif
            </div>
        </div>
    </main>

    {{-- <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Chewr. All rights reserved.</p>
        </div>
    </footer> --}}

</body>
</html>
