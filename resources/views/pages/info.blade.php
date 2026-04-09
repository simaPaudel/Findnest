<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .page-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 56px 20px 72px;
        }

        .page-hero {
            display: grid;
            gap: 28px;
            padding: 28px 0 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .page-eyebrow {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            border-radius: 999px;
            border: 1px solid rgba(255, 56, 92, 0.16);
            background: rgba(255, 56, 92, 0.06);
            color: #e11d48;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .page-heading {
            max-width: 760px;
            font-size: clamp(2rem, 4vw, 3.35rem);
            line-height: 1.04;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin: 0;
        }

        .page-intro {
            max-width: 700px;
            font-size: 1.02rem;
            line-height: 1.85;
            color: #475569;
            margin: 0;
        }

        .page-grid {
            display: grid;
            gap: 22px;
            margin-top: 34px;
        }

        .highlight-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .support-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .info-card,
        .faq-item {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
        }

        .info-card {
            padding: 24px;
        }

        .highlight-label {
            margin: 0;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .highlight-value {
            margin: 10px 0 0;
            font-size: 1rem;
            line-height: 1.65;
            font-weight: 600;
            color: #0f172a;
        }

        .section-title {
            margin: 0 0 14px;
            font-size: 1.3rem;
            line-height: 1.3;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .section-copy {
            margin: 0;
            color: #475569;
            line-height: 1.8;
            font-size: 0.98rem;
        }

        .section-copy + .section-copy {
            margin-top: 12px;
        }

        .section-list {
            margin: 16px 0 0;
            padding-left: 18px;
            color: #475569;
        }

        .section-list li + li {
            margin-top: 10px;
        }

        .meta-grid {
            display: grid;
            gap: 14px;
            margin-top: 18px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .meta-box {
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 16px 18px;
        }

        .meta-label {
            margin: 0;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .meta-value {
            margin: 8px 0 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }

        .faq-list {
            display: grid;
            gap: 16px;
            margin-top: 8px;
        }

        .faq-item {
            padding: 20px 22px;
        }

        .faq-item summary {
            list-style: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-answer {
            margin-top: 12px;
            color: #475569;
            line-height: 1.8;
            font-size: 0.96rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            margin-top: 8px;
        }

        .back-link:hover {
            color: #0f172a;
        }

        @media (max-width: 900px) {
            .highlight-grid,
            .support-grid,
            .meta-grid {
                grid-template-columns: 1fr;
            }

            .page-shell {
                padding-top: 40px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <main class="page-shell">
        <a href="{{ route('home') }}" class="back-link">Back to Home</a>

        <section class="page-hero">
            <span class="page-eyebrow">{{ $eyebrow }}</span>
            <div>
                <h1 class="page-heading">{{ $heading }}</h1>
                <p class="page-intro">{{ $intro }}</p>
            </div>
        </section>

        <div class="page-grid">
            @if(!empty($highlights ?? []))
                <section class="highlight-grid">
                    @foreach($highlights as $highlight)
                        <article class="info-card">
                            <p class="highlight-label">{{ $highlight['label'] }}</p>
                            <p class="highlight-value">{{ $highlight['value'] }}</p>
                        </article>
                    @endforeach
                </section>
            @endif

            @if(!empty($supportCards ?? []))
                <section class="support-grid">
                    @foreach($supportCards as $card)
                        <article class="info-card">
                            <h2 class="section-title">{{ $card['title'] }}</h2>
                            <p class="section-copy">{{ $card['text'] }}</p>
                        </article>
                    @endforeach
                </section>
            @endif

            @if(!empty($sections ?? []))
                @foreach($sections as $section)
                    <section class="info-card">
                        <h2 class="section-title">{{ $section['title'] }}</h2>

                        @foreach($section['body'] ?? [] as $paragraph)
                            <p class="section-copy">{{ $paragraph }}</p>
                        @endforeach

                        @if(!empty($section['bullets'] ?? []))
                            <ul class="section-list">
                                @foreach($section['bullets'] as $bullet)
                                    <li>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['meta'] ?? []))
                            <div class="meta-grid">
                                @foreach($section['meta'] as $meta)
                                    <div class="meta-box">
                                        <p class="meta-label">{{ $meta['label'] }}</p>
                                        <p class="meta-value">{{ $meta['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endforeach
            @endif

            @if(!empty($faqItems ?? []))
                <section class="faq-list">
                    @foreach($faqItems as $item)
                        <details class="faq-item">
                            <summary>{{ $item['question'] }}</summary>
                            <p class="faq-answer">{{ $item['answer'] }}</p>
                        </details>
                    @endforeach
                </section>
            @endif
        </div>
    </main>

    @include('components.footer')
</body>
</html>
