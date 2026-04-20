@php
    $isAbout = ($pageTitle ?? '') === 'About Us';
    $isContact = ($pageTitle ?? '') === 'Contact Us';
    $isHelp = ($pageTitle ?? '') === 'Help Center';
    $isFaq = ($pageTitle ?? '') === 'FAQ';
    $isTerms = ($pageTitle ?? '') === 'Terms & Conditions';
    $isPrivacy = ($pageTitle ?? '') === 'Privacy Policy';

    $aboutValues = [
        [
            'title' => 'Verified Listings',
            'description' => 'Clear property details and moderated approvals help people browse with more confidence.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s7-4 7-11V6l-7-3-7 3v5c0 7 7 11 7 11Z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 12 2 2 4-4"></path></svg>',
        ],
        [
            'title' => 'Secure Booking',
            'description' => 'Booking status, payment progress, and confirmations stay visible in one place.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2" stroke-width="2"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>',
        ],
        [
            'title' => 'Roommate Matching',
            'description' => 'Roommate discovery is built into the platform for users who want shared living options.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="3" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 21v-2a3.5 3.5 0 0 0-2.5-3.4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 4.3a3 3 0 1 1 0 5.4"></path></svg>',
        ],
    ];

    $helpCategories = [
        [
            'title' => 'Account & Login',
            'description' => 'Get help with sign in, password resets, and profile access.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20a8 8 0 0 1 16 0"></path></svg>',
        ],
        [
            'title' => 'Booking Support',
            'description' => 'Check booking status, availability, and invoice details.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="5" y="5" width="14" height="14" rx="2" stroke-width="2"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3v4M16 3v4M5 10h14"></path></svg>',
        ],
        [
            'title' => 'Payments & Verification',
            'description' => 'Understand how payment checks and listing review work.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="2" stroke-width="2"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 14h3"></path></svg>',
        ],
        [
            'title' => 'Roommate Matching',
            'description' => 'Learn how roommate discovery is organized on FindNest.',
            'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="8" cy="8" r="3" stroke-width="2"></circle><circle cx="16.5" cy="9.5" r="2.5" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 20a5 5 0 0 1 10 0M14 20a4.5 4.5 0 0 1 7 0"></path></svg>',
        ],
    ];

    $helpFaqs = [
        [
            'question' => 'How do I book a property?',
            'answer' => 'Open a listing, review the details, and submit a booking request from the property page.',
        ],
        [
            'question' => 'How does payment verification work?',
            'answer' => 'Payments are matched with the booking status before a stay is marked as confirmed.',
        ],
        [
            'question' => 'How are listings verified?',
            'answer' => 'Owners submit property information and images, and listings can be reviewed before they appear prominently.',
        ],
        [
            'question' => 'How does roommate matching work?',
            'answer' => 'The roommate area helps users discover compatible shared-living options from their preferences.',
        ],
        [
            'question' => 'What should I do if I face a booking issue?',
            'answer' => 'Check the booking status first, then contact support with the booking ID and property name.',
        ],
        [
            'question' => 'How can I contact support?',
            'answer' => 'Email support@findnest.com or include your location as Pokhara, Nepal when you reach out.',
        ],
    ];

    $faqSource = !empty($faqItems ?? []) ? $faqItems : $helpFaqs;

    $contactCards = $contactCards ?? [];
    $policyCards = $policyCards ?? [];
    $closingText = $closingText ?? '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --fn-accent: #ff385c;
            --fn-accent-soft: rgba(255, 56, 92, 0.08);
            --fn-accent-border: rgba(255, 56, 92, 0.14);
            --fn-bg: #f8fafc;
            --fn-surface: #ffffff;
            --fn-surface-soft: #fbfdff;
            --fn-ink: #0f172a;
            --fn-muted: #64748b;
            --fn-border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top, rgba(255, 56, 92, 0.04), transparent 34%),
                linear-gradient(180deg, #ffffff 0%, var(--fn-bg) 100%);
            color: var(--fn-ink);
        }

        a {
            color: inherit;
        }

        .page-shell {
            max-width: 1040px;
            margin: 0 auto;
            padding: 48px 20px 72px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            color: var(--fn-muted);
            font-size: 0.94rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.18s ease;
        }

        .back-link:hover {
            color: var(--fn-ink);
        }

        .back-link::before {
            content: '←';
            font-size: 1rem;
            line-height: 1;
        }

        .page-hero {
            padding: 8px 0 28px;
        }

        .page-eyebrow {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            border: 1px solid var(--fn-accent-border);
            background: var(--fn-accent-soft);
            color: #be123c;
            padding: 7px 12px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .page-title {
            margin: 14px 0 0;
            max-width: 760px;
            font-size: clamp(2rem, 4vw, 3.35rem);
            line-height: 1.08;
            letter-spacing: -0.04em;
            font-weight: 800;
        }

        .page-intro {
            margin: 14px 0 0;
            max-width: 720px;
            color: var(--fn-muted);
            font-size: 1.02rem;
            line-height: 1.85;
        }

        .hero-actions {
            margin-top: 22px;
        }

        .primary-link,
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .primary-link {
            padding: 12px 18px;
            background: var(--fn-accent);
            color: #ffffff;
        }

        .primary-link:hover {
            background: #e11d48;
        }

        .secondary-link {
            padding: 11px 17px;
            border: 1px solid var(--fn-border);
            background: #ffffff;
            color: var(--fn-ink);
        }

        .secondary-link:hover {
            border-color: rgba(255, 56, 92, 0.22);
            color: #be123c;
        }

        .content-stack {
            display: grid;
            gap: 22px;
        }

        .section-card {
            border: 1px solid var(--fn-border);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.96);
        }

        .section-pad {
            padding: 24px;
        }

        .split-section {
            display: grid;
            grid-template-columns: minmax(0, 220px) minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .section-kicker {
            margin: 0;
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .section-title {
            margin: 10px 0 0;
            font-size: 1.45rem;
            line-height: 1.2;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .section-copy {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.98rem;
            line-height: 1.85;
        }

        .section-copy + .section-copy {
            margin-top: 10px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .value-card,
        .support-card {
            height: 100%;
            border: 1px solid var(--fn-border);
            border-radius: 20px;
            background: var(--fn-surface);
            padding: 20px;
        }

        .card-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: var(--fn-accent-soft);
            color: var(--fn-accent);
        }

        .card-icon svg {
            width: 20px;
            height: 20px;
        }

        .card-title {
            margin: 14px 0 0;
            font-size: 1rem;
            line-height: 1.35;
            font-weight: 700;
        }

        .card-copy {
            margin: 10px 0 0;
            color: var(--fn-muted);
            font-size: 0.94rem;
            line-height: 1.75;
        }

        .closing-card {
            padding: 22px 24px;
            border: 1px solid var(--fn-border);
            border-radius: 20px;
            background: var(--fn-surface-soft);
        }

        .closing-card p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.98rem;
            line-height: 1.8;
        }

        .hero-center {
            display: grid;
            justify-items: center;
            text-align: center;
            gap: 18px;
        }

        .hero-center .page-title,
        .hero-center .page-intro {
            margin-left: auto;
            margin-right: auto;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .faq-list {
            display: grid;
            gap: 12px;
        }

        .faq-item {
            border: 1px solid var(--fn-border);
            border-radius: 18px;
            background: #ffffff;
            overflow: hidden;
        }

        .faq-item summary {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 20px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--fn-ink);
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-answer {
            margin: 0;
            padding: 0 20px 18px;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.8;
        }

        .chevron {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
            color: #94a3b8;
            transition: transform 0.18s ease;
        }

        .faq-item[open] .chevron {
            transform: rotate(180deg);
        }

        .contact-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 20px;
            align-items: center;
            padding: 24px;
            border: 1px solid var(--fn-border);
            border-radius: 24px;
            background: var(--fn-surface-soft);
        }

        .contact-title {
            margin: 10px 0 0;
            font-size: 1.25rem;
            line-height: 1.25;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .contact-copy {
            margin: 10px 0 0;
            color: var(--fn-muted);
            line-height: 1.8;
        }

        .contact-meta {
            display: grid;
            gap: 12px;
            min-width: 220px;
        }

        .contact-meta-row {
            display: grid;
            gap: 4px;
        }

        .contact-meta-label {
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .contact-meta-value {
            color: var(--fn-ink);
            font-size: 0.96rem;
            font-weight: 600;
            text-decoration: none;
        }

        .contact-actions {
            display: flex;
            align-items: center;
        }

        .faq-hero .page-title {
            max-width: 700px;
        }

        @media (max-width: 980px) {
            .values-grid,
            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .split-section,
            .contact-card {
                grid-template-columns: 1fr;
            }

            .contact-meta {
                min-width: 0;
            }
        }

        @media (max-width: 640px) {
            .page-shell {
                padding: 36px 16px 64px;
            }

            .section-pad,
            .closing-card,
            .contact-card {
                padding: 20px;
            }

            .values-grid,
            .category-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: clamp(1.9rem, 10vw, 2.5rem);
            }

            .faq-item summary {
                padding: 16px 18px;
            }

            .faq-answer {
                padding: 0 18px 16px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <main class="page-shell">
        <a href="{{ route('home') }}" class="back-link">Back to Home</a>

        @if($isAbout)
            <section class="page-hero">
                <span class="page-eyebrow">About FindNest</span>
                <h1 class="page-title">Helping people find secure stays and compatible roommates</h1>
                <p class="page-intro">
                    FindNest brings accommodation listings and roommate discovery into one place so people can compare options, understand availability, and move forward with more clarity.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('listings.index') }}" class="primary-link">Browse Listings</a>
                </div>
            </section>

            <div class="content-stack">
                <section class="section-card section-pad split-section">
                    <div>
                        <p class="section-kicker">Our Mission</p>
                        <h2 class="section-title">Our Mission</h2>
                    </div>
                    <div>
                        <p class="section-copy">
                            Our mission is to make housing search feel simpler and more trustworthy by presenting clear listings, practical booking steps, and a straightforward way to discover roommates when shared living makes sense.
                        </p>
                    </div>
                </section>

                <section class="values-grid">
                    @foreach($aboutValues as $value)
                        <article class="value-card">
                            <span class="card-icon" aria-hidden="true">{!! $value['icon'] !!}</span>
                            <h2 class="card-title">{{ $value['title'] }}</h2>
                            <p class="card-copy">{{ $value['description'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="closing-card">
                    <p>
                        FindNest keeps the focus on clearer listings, transparent booking, and a calmer way to search for housing and roommates.
                    </p>
                </section>
            </div>
        @elseif($isContact)
            <section class="page-hero hero-center">
                <span class="page-eyebrow">Contact Us</span>
                <div>
                    <h1 class="page-title">Get in touch with the FindNest team</h1>
                    <p class="page-intro">
                        Use the contact details below for booking issues, account questions, or listing support. We keep the process simple and direct.
                    </p>
                </div>

                <div class="hero-actions">
                    <a href="mailto:support@findnest.com" class="primary-link">Email Us</a>
                </div>
            </section>

            <div class="content-stack">
                <section class="values-grid">
                    @foreach($contactCards as $card)
                        <article class="value-card">
                            <span class="card-icon" aria-hidden="true">{!! $card['icon'] !!}</span>
                            <h2 class="card-title">{{ $card['title'] }}</h2>
                            <p class="card-copy">{{ $card['value'] }}</p>
                            <p class="card-copy" style="margin-top: 8px;">{{ $card['note'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="closing-card">
                    <p>
                        If your message is about a booking or payment, include the booking ID, property title, and the email used on the account so the team can help faster.
                    </p>
                </section>
            </div>
        @elseif($isHelp)
            <section class="page-hero hero-center">
                <span class="page-eyebrow">Help Center</span>
                <div>
                    <h1 class="page-title">How can FindNest help?</h1>
                    <p class="page-intro">
                        Use the support categories below for common issues, or contact the team directly if you need help with a specific account or booking.
                    </p>
                </div>
            </section>

            <div class="content-stack">
                <section class="section-card section-pad">
                    <div style="margin-bottom: 18px;">
                        <p class="section-kicker">Support categories</p>
                        <h2 class="section-title">Start with the topic that fits your issue</h2>
                    </div>

                    <div class="category-grid">
                        @foreach($helpCategories as $category)
                            <article class="support-card">
                                <span class="card-icon" aria-hidden="true">{!! $category['icon'] !!}</span>
                                <h3 class="card-title">{{ $category['title'] }}</h3>
                                <p class="card-copy">{{ $category['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="contact-card">
                    <div>
                        <p class="section-kicker">Contact us</p>
                        <h2 class="contact-title">Need help from the FindNest team?</h2>
                        <p class="contact-copy">
                            If the issue is not covered above, reach out with your booking ID or property name so the team can respond faster.
                        </p>
                    </div>

                    <div class="contact-meta">
                        <div class="contact-meta-row">
                            <span class="contact-meta-label">Email</span>
                            <a class="contact-meta-value" href="mailto:support@findnest.com">support@findnest.com</a>
                        </div>
                        <div class="contact-meta-row">
                            <span class="contact-meta-label">Location</span>
                            <span class="contact-meta-value">Pokhara, Nepal</span>
                        </div>
                    </div>

                    <div class="contact-actions">
                        <a class="primary-link" href="{{ route('pages.contact') }}">Contact Us</a>
                    </div>
                </section>
            </div>
        @elseif($isTerms || $isPrivacy)
            <section class="page-hero hero-center">
                <span class="page-eyebrow">{{ $eyebrow }}</span>
                <div>
                    <h1 class="page-title">{{ $heading }}</h1>
                    <p class="page-intro">
                        {{ $intro }}
                    </p>
                </div>
            </section>

            <div class="content-stack">
                <section class="values-grid">
                    @foreach($policyCards as $card)
                        <article class="value-card">
                            <span class="card-icon" aria-hidden="true">{!! $card['icon'] !!}</span>
                            <h2 class="card-title">{{ $card['title'] }}</h2>
                            <p class="card-copy">{{ $card['description'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="closing-card">
                    <p>{{ $closingText }}</p>
                </section>
            </div>
        @else
            <section class="page-hero hero-center faq-hero">
                <span class="page-eyebrow">Frequently Asked Questions</span>
                <div>
                    <h1 class="page-title">Answers to common FindNest questions</h1>
                    <p class="page-intro">
                        These answers cover the basics of listings, bookings, payments, and reviews on FindNest.
                    </p>
                </div>
            </section>

            <section class="section-card section-pad">
                <div class="faq-list">
                    @foreach($faqSource as $item)
                        <details class="faq-item">
                            <summary>
                                <span>{{ $item['question'] }}</span>
                                <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"></path>
                                </svg>
                            </summary>
                            <p class="faq-answer">{{ $item['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('components.footer')
</body>
</html>
