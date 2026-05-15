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

    $aboutWhyItems = [
        'Clear listing information helps users compare rooms, homes, prices, and locations without unnecessary confusion.',
        'Booking steps are kept visible so users can understand requests, payment progress, and confirmation status.',
        'Roommate discovery supports shared-living decisions with a more organized and focused experience.',
    ];

    $aboutTrustItems = [
        'Listings are structured to show practical property information before users make a decision.',
        'Booking and payment status remain connected to the user account for clearer follow-up.',
        'User conversations support direct communication before moving forward with housing decisions.',
        'The platform keeps user and owner roles separated so existing access rules remain consistent.',
    ];

    $contactGuidanceItems = [
        'Booking issues: include the booking ID, property title, move-in date, and the email used on your account.',
        'Payment questions: include the payment reference, booking ID, payment date, and a short description of the issue.',
        'Listing or property issues: include the property name, owner name if available, and the detail that needs review.',
        'Account or login problems: include your registered email and a clear explanation of what happens when you try to sign in.',
    ];

    $termsSections = [
        [
            'title' => 'User Accounts',
            'body' => 'Users are responsible for keeping account details accurate and secure. Each account should represent the person using FindNest, and login details should not be shared with others.',
        ],
        [
            'title' => 'Property Listings',
            'body' => 'Owners should provide clear, honest, and current information about properties, rooms, prices, availability, location, and images. Misleading or incomplete listings may be reviewed, hidden, or removed.',
        ],
        [
            'title' => 'Booking and Payments',
            'body' => 'Before sending a booking request or payment, users should review the listing, selected room, price, booking term, and invoice details. Payment status and booking progress are shown inside the user account for transparency.',
        ],
        [
            'title' => 'Roommate Interactions',
            'body' => 'Roommate matching and messaging are provided to help users explore shared-living options. Users should communicate respectfully, avoid false information, and make final housing decisions carefully.',
        ],
        [
            'title' => 'Content and Reviews',
            'body' => 'Photos, messages, reviews, and profile information should be relevant, respectful, and accurate. Reviews may be moderated before appearing publicly to keep the platform useful and fair.',
        ],
        [
            'title' => 'Privacy and Safety',
            'body' => 'FindNest uses account, listing, booking, payment, and support information only to operate the platform and improve user safety. Users should avoid sharing sensitive personal or financial details in public areas or messages.',
        ],
        [
            'title' => 'Platform Responsibilities',
            'body' => 'FindNest provides a structured academic housing platform for listing discovery, booking workflows, roommate matching, and support. The platform aims to keep information organized but cannot guarantee every real-world housing outcome.',
        ],
        [
            'title' => 'Account Suspension or Removal',
            'body' => 'Accounts, listings, reviews, or messages may be restricted or removed if they are abusive, misleading, fraudulent, unsafe, or harmful to other users or the integrity of the platform.',
        ],
        [
            'title' => 'Limitation of Liability',
            'body' => 'FindNest is developed as a Final Year Project to demonstrate realistic accommodation and roommate workflows. Users should verify important housing details independently before making final rental or roommate decisions.',
        ],
    ];

    $privacySections = [
        [
            'title' => 'Information We Collect',
            'body' => 'FindNest collects the information needed to run housing search, booking, payment tracking, roommate matching, messaging, reviews, and support features. This may include account details, listing activity, booking records, and content submitted by users.',
        ],
        [
            'title' => 'Account and Profile Data',
            'body' => 'When users create or update an account, FindNest may store names, email addresses, phone numbers, profile photos, gender selection, role information, and basic profile details required for account access and platform use.',
        ],
        [
            'title' => 'Booking and Payment Information',
            'body' => 'Booking requests, selected rooms, rental dates, invoices, payment status, transaction references, and booking history are stored so users, owners, and administrators can track accommodation activity clearly.',
        ],
        [
            'title' => 'Roommate Preference Data',
            'body' => 'Roommate matching may use preferences such as location, budget, lifestyle choices, gender preference, and profile answers. These details help FindNest show more relevant shared-living matches.',
        ],
        [
            'title' => 'How Information Is Used',
            'body' => 'Information is used to manage accounts, display listings, process booking workflows, support payments, recommend roommate matches, send relevant updates, prevent misuse, and improve the reliability of the platform.',
        ],
        [
            'title' => 'Communication and Notifications',
            'body' => 'FindNest may use account and booking information to show in-app notifications, support messages, booking updates, payment status changes, listing responses, and important account-related communication.',
        ],
        [
            'title' => 'Data Protection and Security',
            'body' => 'Reasonable security practices are used to protect stored information, including authentication controls and role-based access. Users should also keep passwords private and avoid sharing sensitive data in public fields or messages.',
        ],
        [
            'title' => 'User Rights and Account Control',
            'body' => 'Users can update profile details, manage saved listings, review booking activity, and contact support for privacy-related questions. Some records may be retained when needed for booking, payment, moderation, or academic demonstration integrity.',
        ],
        [
            'title' => 'Third-Party Services',
            'body' => 'FindNest may connect with third-party services for authentication, payment verification, maps, email, or hosting. These services may process limited information necessary to complete the requested feature.',
        ],
        [
            'title' => 'Contact and Privacy Questions',
            'body' => 'For privacy questions, users can contact support@findnest.com with the account email and a clear description of the request so the team can review it responsibly.',
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

        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top, rgba(255, 56, 92, 0.04), transparent 34%),
                linear-gradient(180deg, #ffffff 0%, var(--fn-bg) 100%);
            color: var(--fn-ink);
            overflow-x: hidden;
        }

        img,
        video,
        canvas,
        svg {
            max-width: 100%;
        }

        img,
        video {
            height: auto;
        }

        input,
        select,
        textarea,
        button {
            max-width: 100%;
            font: inherit;
        }

        a {
            color: inherit;
        }

        .page-shell {
            max-width: 1040px;
            margin: 0 auto;
            padding: 48px 20px 72px;
            min-width: 0;
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
            border-left: 3px solid rgba(255, 56, 92, 0.48);
            color: #e11d48;
            padding: 2px 0 2px 10px;
            font-size: 0.84rem;
            font-weight: 650;
            letter-spacing: 0.01em;
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
            min-width: 0;
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
            color: #e11d48;
            font-size: 0.86rem;
            font-weight: 650;
            letter-spacing: 0.01em;
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
            min-width: 0;
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

        .about-layout {
            display: grid;
            gap: 24px;
        }

        .about-hero-card {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(260px, 0.65fr);
            gap: 28px;
            align-items: center;
            padding: 34px;
            border: 1px solid var(--fn-border);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 18px 44px rgba(15, 23, 42, 0.06);
        }

        .about-hero-card .page-title {
            max-width: 820px;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .about-hero-panel {
            border: 1px solid var(--fn-accent-border);
            border-radius: 24px;
            background: #fff7f9;
            padding: 22px;
        }

        .about-panel-label {
            margin: 0;
            color: #e11d48;
            font-size: 0.84rem;
            font-weight: 650;
            letter-spacing: 0.01em;
        }

        .about-panel-title {
            margin: 10px 0 0;
            font-size: 1.22rem;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .about-panel-copy {
            margin: 10px 0 0;
            color: var(--fn-muted);
            font-size: 0.94rem;
            line-height: 1.75;
        }

        .about-section-heading {
            margin-bottom: 18px;
        }

        .about-section-heading .section-title {
            max-width: 620px;
        }

        .about-section-heading .section-copy {
            max-width: 760px;
            margin-top: 10px;
        }

        .about-list {
            display: grid;
            gap: 12px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .about-list li {
            position: relative;
            padding: 14px 16px 14px 42px;
            border: 1px solid var(--fn-border);
            border-radius: 16px;
            background: #ffffff;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.7;
        }

        .about-list li::before {
            content: "";
            position: absolute;
            left: 16px;
            top: 22px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--fn-accent);
            box-shadow: 0 0 0 4px rgba(255, 56, 92, 0.1);
        }

        .about-trust-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .about-trust-card {
            border: 1px solid var(--fn-border);
            border-radius: 18px;
            background: #ffffff;
            padding: 18px;
        }

        .about-trust-card p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.95rem;
            line-height: 1.75;
        }

        .about-cta {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            padding: 26px;
            border: 1px solid var(--fn-accent-border);
            border-radius: 24px;
            background: #fff7f9;
        }

        .about-cta h2 {
            margin: 0;
            font-size: 1.45rem;
            line-height: 1.25;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .about-cta p {
            margin: 8px 0 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.75;
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
            gap: 14px;
        }

        .faq-item {
            border: 1px solid var(--fn-border);
            border-radius: 20px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .faq-item:hover {
            border-color: rgba(255, 56, 92, 0.18);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.055);
            transform: translateY(-1px);
        }

        .faq-item[open] {
            border-color: rgba(255, 56, 92, 0.2);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        }

        .faq-item summary {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 20px 22px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--fn-ink);
            line-height: 1.45;
            transition: color 0.18s ease, background 0.18s ease;
        }

        .faq-item summary:hover {
            color: #e11d48;
        }

        .faq-item[open] summary {
            color: #e11d48;
        }

        .faq-item summary:focus-visible {
            outline: none;
            background: rgba(255, 56, 92, 0.045);
            box-shadow: inset 3px 0 0 rgba(255, 56, 92, 0.45);
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-answer {
            margin: 0;
            padding: 0 22px 22px;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.82;
        }

        .chevron {
            width: 28px;
            height: 28px;
            flex: 0 0 auto;
            padding: 6px;
            border-radius: 999px;
            background: #f8fafc;
            color: #94a3b8;
            transition: transform 0.18s ease, color 0.18s ease, background 0.18s ease;
        }

        .faq-item:hover .chevron,
        .faq-item[open] .chevron {
            background: rgba(255, 56, 92, 0.08);
            color: #e11d48;
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
            font-size: 0.8rem;
            font-weight: 650;
            letter-spacing: 0.01em;
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

        .contact-page {
            display: grid;
            gap: 24px;
        }

        .contact-hero {
            padding: 18px 0 30px;
        }

        .contact-hero .page-title {
            max-width: 680px;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .contact-hero .page-intro {
            max-width: 660px;
            font-size: 1rem;
            line-height: 1.78;
        }

        .contact-card-grid {
            gap: 16px;
        }

        .contact-info-card {
            padding: 22px;
            border-color: #e5eaf1;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .contact-info-card:hover {
            border-color: rgba(255, 56, 92, 0.18);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
            transform: translateY(-2px);
        }

        .contact-info-card .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 56, 92, 0.07);
        }

        .contact-info-card .card-icon svg {
            width: 19px;
            height: 19px;
        }

        .contact-guidance-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .contact-guidance-head {
            max-width: 680px;
            margin-bottom: 20px;
        }

        .contact-guidance-list {
            display: grid;
            gap: 12px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .contact-guidance-list li {
            position: relative;
            padding: 14px 16px 14px 42px;
            border: 1px solid #e5eaf1;
            border-radius: 16px;
            background: #ffffff;
            color: var(--fn-muted);
            font-size: 0.95rem;
            line-height: 1.72;
        }

        .contact-guidance-list li::before {
            content: "";
            position: absolute;
            top: 22px;
            left: 16px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--fn-accent);
            box-shadow: 0 0 0 4px rgba(255, 56, 92, 0.09);
        }

        .contact-form-card {
            display: grid;
            gap: 18px;
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .contact-form-head {
            max-width: 720px;
        }

        .contact-form {
            display: grid;
            gap: 16px;
        }

        .contact-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .contact-form-group {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .contact-form-group.is-wide {
            grid-column: 1 / -1;
        }

        .contact-form-label {
            color: var(--fn-ink);
            font-size: 0.88rem;
            font-weight: 700;
        }

        .contact-form-input,
        .contact-form-textarea {
            width: 100%;
            border: 1px solid #dbe4ee;
            border-radius: 14px;
            background: #ffffff;
            color: var(--fn-ink);
            font: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .contact-form-input {
            min-height: 46px;
            padding: 0 14px;
        }

        .contact-form-textarea {
            min-height: 150px;
            resize: vertical;
            padding: 13px 14px;
            line-height: 1.65;
        }

        .contact-form-input:focus,
        .contact-form-textarea:focus {
            border-color: rgba(255, 56, 92, 0.44);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .contact-form-input.is-invalid,
        .contact-form-textarea.is-invalid {
            border-color: #fca5a5;
        }

        .contact-form-error {
            color: #be123c;
            font-size: 0.8rem;
            line-height: 1.45;
        }

        .contact-form-alert {
            padding: 13px 14px;
            border-radius: 14px;
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .contact-form-alert.success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #047857;
        }

        .contact-form-alert.error {
            border: 1px solid #fecdd3;
            background: #fff1f2;
            color: #be123c;
        }

        .contact-form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .contact-form-note {
            margin: 0;
            max-width: 460px;
            color: var(--fn-muted);
            font-size: 0.86rem;
            line-height: 1.65;
        }

        .contact-form-actions button.primary-link {
            border: 0;
            cursor: pointer;
            font: inherit;
        }

        .contact-support-note {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            padding: 24px;
            border: 1px solid rgba(255, 56, 92, 0.14);
            border-radius: 24px;
            background: #fff8fa;
        }

        .contact-support-note h2 {
            margin: 0;
            font-size: 1.25rem;
            line-height: 1.28;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .contact-support-note p {
            margin: 8px 0 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.75;
        }

        .contact-support-note .primary-link {
            border-radius: 14px;
            box-shadow: 0 10px 20px rgba(255, 56, 92, 0.12);
            transition: background 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .contact-support-note .primary-link:hover {
            box-shadow: 0 12px 24px rgba(255, 56, 92, 0.18);
            transform: translateY(-1px);
        }

        .terms-page {
            display: grid;
            gap: 24px;
        }

        .terms-hero {
            padding: 18px 0 30px;
        }

        .terms-hero .page-title {
            max-width: 720px;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .terms-hero .page-intro {
            max-width: 700px;
            font-size: 1rem;
            line-height: 1.78;
        }

        .terms-summary {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(220px, 0.42fr);
            gap: 18px;
            align-items: center;
            padding: 24px;
            border: 1px solid var(--fn-border);
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .terms-summary h2 {
            margin: 0;
            font-size: 1.32rem;
            line-height: 1.28;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .terms-summary p {
            margin: 9px 0 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.75;
        }

        .terms-summary-meta {
            display: grid;
            gap: 10px;
            padding: 18px;
            border: 1px solid rgba(255, 56, 92, 0.14);
            border-radius: 18px;
            background: #fff8fa;
        }

        .terms-summary-meta span {
            color: #e11d48;
            font-size: 0.84rem;
            font-weight: 650;
        }

        .terms-summary-meta strong {
            color: var(--fn-ink);
            font-size: 0.98rem;
            line-height: 1.45;
        }

        .terms-list-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .terms-list {
            display: grid;
            gap: 12px;
            margin: 0;
            padding: 0;
            list-style: none;
            counter-reset: terms-counter;
        }

        .terms-item {
            position: relative;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 14px;
            padding: 18px;
            border: 1px solid #e5eaf1;
            border-radius: 18px;
            background: #ffffff;
            counter-increment: terms-counter;
        }

        .terms-item::before {
            content: counter(terms-counter, decimal-leading-zero);
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 56, 92, 0.07);
            color: #e11d48;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .terms-item h2 {
            margin: 0;
            font-size: 1.02rem;
            line-height: 1.35;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .terms-item p {
            margin: 8px 0 0;
            color: var(--fn-muted);
            font-size: 0.95rem;
            line-height: 1.78;
        }

        .terms-note {
            padding: 22px 24px;
            border: 1px solid rgba(255, 56, 92, 0.14);
            border-radius: 22px;
            background: #fff8fa;
        }

        .terms-note p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.78;
        }

        .privacy-page {
            display: grid;
            gap: 24px;
        }

        .privacy-hero {
            padding: 18px 0 30px;
        }

        .privacy-hero .page-title {
            max-width: 720px;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .privacy-hero .page-intro {
            max-width: 700px;
            font-size: 1rem;
            line-height: 1.78;
        }

        .privacy-summary {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(220px, 0.42fr);
            gap: 18px;
            align-items: center;
            padding: 24px;
            border: 1px solid var(--fn-border);
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .privacy-summary h2 {
            margin: 0;
            font-size: 1.32rem;
            line-height: 1.28;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .privacy-summary p {
            margin: 9px 0 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.75;
        }

        .privacy-summary-meta {
            display: grid;
            gap: 10px;
            padding: 18px;
            border: 1px solid rgba(255, 56, 92, 0.14);
            border-radius: 18px;
            background: #fff8fa;
        }

        .privacy-summary-meta span {
            color: #e11d48;
            font-size: 0.84rem;
            font-weight: 650;
        }

        .privacy-summary-meta strong {
            color: var(--fn-ink);
            font-size: 0.98rem;
            line-height: 1.45;
        }

        .privacy-list-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .privacy-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .privacy-item {
            padding: 20px;
            border: 1px solid #e5eaf1;
            border-radius: 18px;
            background: #ffffff;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .privacy-item:hover {
            border-color: rgba(255, 56, 92, 0.18);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.055);
            transform: translateY(-2px);
        }

        .privacy-item h2 {
            margin: 0;
            font-size: 1.02rem;
            line-height: 1.35;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .privacy-item p {
            margin: 9px 0 0;
            color: var(--fn-muted);
            font-size: 0.95rem;
            line-height: 1.78;
        }

        .privacy-note {
            padding: 22px 24px;
            border: 1px solid rgba(255, 56, 92, 0.14);
            border-radius: 22px;
            background: #fff8fa;
        }

        .privacy-note p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.96rem;
            line-height: 1.78;
        }

        .faq-hero .page-title {
            max-width: 700px;
        }

        .faq-page {
            display: grid;
            gap: 24px;
        }

        .faq-page .faq-hero {
            padding: 18px 0 30px;
        }

        .faq-page .faq-hero .page-title {
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .faq-page .faq-hero .page-intro {
            max-width: 660px;
            font-size: 1rem;
            line-height: 1.78;
        }

        .faq-page .section-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .help-page {
            display: grid;
            gap: 24px;
        }

        .help-hero {
            padding: 18px 0 30px;
        }

        .help-hero .page-title {
            max-width: 680px;
            font-size: clamp(1.45rem, 2.4vw, 2rem);
        }

        .help-hero .page-intro {
            max-width: 650px;
            font-size: 1rem;
            line-height: 1.78;
        }

        .help-section-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .help-section-head {
            margin-bottom: 22px;
            max-width: 620px;
        }

        .help-section-head .section-title {
            margin-top: 8px;
        }

        .help-category-grid {
            gap: 16px;
        }

        .help-support-card {
            display: flex;
            flex-direction: column;
            min-height: 210px;
            padding: 22px;
            border-color: #e5eaf1;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .help-support-card:hover {
            border-color: rgba(255, 56, 92, 0.18);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
            transform: translateY(-2px);
        }

        .help-support-card .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 56, 92, 0.07);
        }

        .help-support-card .card-icon svg {
            width: 19px;
            height: 19px;
        }

        .help-support-card .card-title {
            margin-top: 16px;
            font-size: 1.02rem;
        }

        .help-support-card .card-copy {
            line-height: 1.72;
        }

        .help-contact-card {
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.045);
        }

        .help-contact-card .primary-link {
            border-radius: 14px;
            box-shadow: 0 10px 20px rgba(255, 56, 92, 0.12);
            transition: background 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .help-contact-card .primary-link:hover {
            box-shadow: 0 12px 24px rgba(255, 56, 92, 0.18);
            transform: translateY(-1px);
        }

        .help-contact-card .contact-meta {
            padding: 2px 0;
        }

        .help-contact-card .contact-meta-value:hover {
            color: #e11d48;
        }

        @media (max-width: 980px) {
            .about-hero-card,
            .about-cta {
                grid-template-columns: 1fr;
            }

            .values-grid,
            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .split-section,
            .contact-card,
            .contact-support-note,
            .contact-form-grid,
            .terms-summary,
            .privacy-summary {
                grid-template-columns: 1fr;
            }

            .contact-meta {
                min-width: 0;
            }

            .help-support-card {
                min-height: 0;
            }

            .contact-info-card {
                min-height: 0;
            }
        }

        @media (max-width: 640px) {
            .page-shell {
                padding: 36px 16px 64px;
            }

            .section-pad,
            .closing-card,
            .contact-card,
            .about-hero-card,
            .about-cta {
                padding: 20px;
            }

            .values-grid,
            .category-grid,
            .about-trust-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: clamp(1.9rem, 10vw, 2.5rem);
            }

            .faq-item summary {
                align-items: flex-start;
                padding: 18px 18px;
            }

            .faq-answer {
                padding: 0 18px 16px;
            }

            .faq-page .faq-hero {
                padding-top: 10px;
                padding-bottom: 24px;
            }

            .faq-page .section-card {
                border-radius: 20px;
            }

            .chevron {
                width: 26px;
                height: 26px;
                margin-top: -1px;
            }

            .help-hero {
                padding-top: 10px;
                padding-bottom: 24px;
            }

            .help-section-card,
            .help-contact-card {
                border-radius: 20px;
            }

            .help-support-card {
                padding: 20px;
            }

            .help-contact-card .primary-link {
                width: 100%;
            }

            .contact-hero {
                padding-top: 10px;
                padding-bottom: 24px;
            }

            .contact-info-card,
            .contact-guidance-card,
            .contact-form-card,
            .contact-support-note {
                border-radius: 20px;
            }

            .contact-info-card,
            .contact-form-card,
            .contact-support-note {
                padding: 20px;
            }

            .contact-form-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .contact-support-note .primary-link,
            .contact-form-actions .primary-link {
                width: 100%;
            }

            .terms-hero {
                padding-top: 10px;
                padding-bottom: 24px;
            }

            .terms-summary,
            .terms-list-card,
            .terms-note {
                border-radius: 20px;
            }

            .terms-summary,
            .terms-note {
                padding: 20px;
            }

            .terms-item {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 16px;
            }

            .privacy-hero {
                padding-top: 10px;
                padding-bottom: 24px;
            }

            .privacy-summary,
            .privacy-list-card,
            .privacy-note {
                border-radius: 20px;
            }

            .privacy-summary,
            .privacy-note {
                padding: 20px;
            }

            .privacy-list {
                grid-template-columns: 1fr;
            }

            .privacy-item {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <main class="page-shell">
        <a href="{{ route('home') }}" class="back-link">Back to Home</a>

        @if($isAbout)
            <div class="about-layout">
                <section class="about-hero-card">
                    <div>
                        <span class="page-eyebrow">About FindNest</span>
                        <h1 class="page-title">A cleaner way to find stays, book confidently, and connect with roommates.</h1>
                        <p class="page-intro">
                            FindNest brings property discovery, booking support, and roommate matching into one focused platform for people who want housing decisions to feel clearer, safer, and better organized.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('listings.index') }}" class="primary-link">Browse Listings</a>
                        </div>
                    </div>

                    <aside class="about-hero-panel" aria-label="FindNest focus">
                        <p class="about-panel-label">Platform focus</p>
                        <h2 class="about-panel-title">Minimal design, practical information, and user-first housing tools.</h2>
                        <p class="about-panel-copy">
                            The experience is built around clear listings, structured booking actions, direct messaging, and roommate discovery without unnecessary complexity.
                        </p>
                    </aside>
                </section>

                <section class="section-card section-pad split-section">
                    <div>
                        <p class="section-kicker">Mission</p>
                        <h2 class="section-title">Our Mission</h2>
                    </div>
                    <div>
                        <p class="section-copy">
                            Our mission is to make housing search more transparent and manageable by helping users evaluate listings, communicate with owners, follow booking progress, and explore compatible shared-living options from one reliable place.
                        </p>
                    </div>
                </section>

                <section class="section-card section-pad">
                    <div class="about-section-heading">
                        <p class="section-kicker">Why FindNest</p>
                        <h2 class="section-title">Built for people who need clarity before choosing a place.</h2>
                        <p class="section-copy">
                            FindNest keeps the housing journey organized by combining the most important discovery and decision-making steps into a simple, consistent interface.
                        </p>
                    </div>

                    <ul class="about-list">
                        @foreach($aboutWhyItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>

                <section class="section-card section-pad">
                    <div class="about-section-heading">
                        <p class="section-kicker">Key Features</p>
                        <h2 class="section-title">Essential tools for safer and more informed housing decisions.</h2>
                    </div>

                    <div class="values-grid">
                        @foreach($aboutValues as $value)
                            <article class="value-card">
                                <span class="card-icon" aria-hidden="true">{!! $value['icon'] !!}</span>
                                <h3 class="card-title">{{ $value['title'] }}</h3>
                                <p class="card-copy">{{ $value['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="section-card section-pad">
                    <div class="about-section-heading">
                        <p class="section-kicker">Trust and Safety</p>
                        <h2 class="section-title">Designed to support responsible browsing, booking, and communication.</h2>
                    </div>

                    <div class="about-trust-grid">
                        @foreach($aboutTrustItems as $item)
                            <article class="about-trust-card">
                                <p>{{ $item }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="about-cta">
                    <div>
                        <h2>Ready to explore available stays?</h2>
                        <p>
                            Browse FindNest listings to compare spaces, review details, and move forward with a cleaner housing search experience.
                        </p>
                    </div>
                    <a href="{{ route('listings.index') }}" class="primary-link">Browse Listings</a>
                </section>
            </div>
        @elseif($isContact)
            <div class="contact-page">
                <section class="page-hero hero-center contact-hero">
                    <span class="page-eyebrow">Contact Us</span>
                    <div>
                        <h1 class="page-title">Reach the FindNest support team with the right details.</h1>
                        <p class="page-intro">
                            For booking, payment, listing, or account questions, send a clear message with the information needed to review your issue quickly and responsibly.
                        </p>
                    </div>

                    <div class="hero-actions">
                        <a href="#contact-form" class="primary-link">Send Message</a>
                    </div>
                </section>

                <section class="values-grid contact-card-grid">
                    @foreach($contactCards as $card)
                        <article class="value-card contact-info-card">
                            <span class="card-icon" aria-hidden="true">{!! $card['icon'] !!}</span>
                            <h2 class="card-title">{{ $card['title'] }}</h2>
                            <p class="card-copy">{{ $card['value'] }}</p>
                            <p class="card-copy" style="margin-top: 8px;">{{ $card['note'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="section-card section-pad contact-guidance-card">
                    <div class="contact-guidance-head">
                        <p class="section-kicker">Support guidance</p>
                        <h2 class="section-title">What to include in your message</h2>
                        <p class="section-copy">
                            Adding the right details helps the team understand your request and respond with less back-and-forth.
                        </p>
                    </div>

                    <ul class="contact-guidance-list">
                        @foreach($contactGuidanceItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>

                <section id="contact-form" class="section-card section-pad contact-form-card">
                    <div class="contact-form-head">
                        <p class="section-kicker">Contact form</p>
                        <h2 class="section-title">Send a message to FindNest support</h2>
                        <p class="section-copy">
                            Share your question with clear details. The message will be sent to the configured FindNest support email.
                        </p>
                    </div>

                    @if(session('contact_success'))
                        <div class="contact-form-alert success" role="status">
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    @if(session('contact_error'))
                        <div class="contact-form-alert error" role="alert">
                            {{ session('contact_error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="contact-form-alert error" role="alert">
                            Please review the highlighted fields and submit the form again.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pages.contact.send') }}" class="contact-form" novalidate>
                        @csrf

                        <div class="contact-form-grid">
                            <div class="contact-form-group">
                                <label for="contact_name" class="contact-form-label">Name</label>
                                <input
                                    type="text"
                                    id="contact_name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="contact-form-input @error('name') is-invalid @enderror"
                                    placeholder="Your full name"
                                    autocomplete="name"
                                    required
                                >
                                @error('name')
                                    <span class="contact-form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="contact-form-group">
                                <label for="contact_email" class="contact-form-label">Email</label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="contact-form-input @error('email') is-invalid @enderror"
                                    placeholder="you@example.com"
                                    autocomplete="email"
                                    required
                                >
                                @error('email')
                                    <span class="contact-form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="contact-form-group is-wide">
                                <label for="contact_subject" class="contact-form-label">Subject</label>
                                <input
                                    type="text"
                                    id="contact_subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    class="contact-form-input @error('subject') is-invalid @enderror"
                                    placeholder="Booking, payment, listing, account, or general support"
                                    required
                                >
                                @error('subject')
                                    <span class="contact-form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="contact-form-group is-wide">
                                <label for="contact_message" class="contact-form-label">Message</label>
                                <textarea
                                    id="contact_message"
                                    name="message"
                                    class="contact-form-textarea @error('message') is-invalid @enderror"
                                    placeholder="Explain your issue clearly. Include booking ID, property name, or account email if relevant."
                                    required
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="contact-form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="contact-form-actions">
                            <p class="contact-form-note">
                                For faster support, include the account email, booking ID, or property title when relevant.
                            </p>
                            <button type="submit" class="primary-link">Send Message</button>
                        </div>
                    </form>
                </section>

            </div>
        @elseif($isHelp)
            <div class="help-page">
                <section class="page-hero hero-center help-hero">
                    <span class="page-eyebrow">Help Center</span>
                    <div>
                        <h1 class="page-title">How can FindNest help?</h1>
                        <p class="page-intro">
                            Use the support categories below for common issues, or contact the team directly if you need help with a specific account or booking.
                        </p>
                    </div>
                </section>

                <section class="section-card section-pad help-section-card">
                    <div class="help-section-head">
                        <p class="section-kicker">Support categories</p>
                        <h2 class="section-title">Start with the topic that fits your issue</h2>
                    </div>

                    <div class="category-grid help-category-grid">
                        @foreach($helpCategories as $category)
                            <article class="support-card help-support-card">
                                <span class="card-icon" aria-hidden="true">{!! $category['icon'] !!}</span>
                                <h3 class="card-title">{{ $category['title'] }}</h3>
                                <p class="card-copy">{{ $category['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="contact-card help-contact-card">
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
        @elseif($isTerms)
            <div class="terms-page">
                <section class="page-hero hero-center terms-hero">
                    <span class="page-eyebrow">Terms &amp; Conditions</span>
                    <div>
                        <h1 class="page-title">Clear terms for using FindNest responsibly.</h1>
                        <p class="page-intro">
                            These terms explain how users, owners, and visitors should use FindNest for accommodation listings, bookings, payments, roommate discovery, reviews, and platform communication.
                        </p>
                    </div>
                </section>

                <section class="terms-summary">
                    <div>
                        <p class="section-kicker">Platform agreement</p>
                        <h2>Using FindNest means following fair, accurate, and respectful housing practices.</h2>
                        <p>
                            The platform is designed as an academic Final Year Project with realistic housing workflows. These terms help keep the experience understandable, safe, and professionally organized.
                        </p>
                    </div>

                    <aside class="terms-summary-meta" aria-label="Terms summary">
                        <span>Applies to</span>
                        <strong>Renters, owners, roommate users, and visitors using FindNest features.</strong>
                    </aside>
                </section>

                <section class="section-card section-pad terms-list-card">
                    <ol class="terms-list">
                        @foreach($termsSections as $section)
                            <li class="terms-item">
                                <div>
                                    <h2>{{ $section['title'] }}</h2>
                                    <p>{{ $section['body'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="terms-note">
                    <p>
                        These Terms &amp; Conditions are written for the FindNest project context and should be read together with the Privacy Policy. For support questions, contact support@findnest.com.
                    </p>
                </section>
            </div>
        @elseif($isPrivacy)
            <div class="privacy-page">
                <section class="page-hero hero-center privacy-hero">
                    <span class="page-eyebrow">Privacy Policy</span>
                    <div>
                        <h1 class="page-title">How FindNest handles user and housing data.</h1>
                        <p class="page-intro">
                            This policy explains what information FindNest collects, why it is used, and how users can stay informed while using listings, bookings, payments, roommate matching, messages, and support features.
                        </p>
                    </div>
                </section>

                <section class="privacy-summary">
                    <div>
                        <p class="section-kicker">Privacy overview</p>
                        <h2>FindNest uses information to make housing workflows clearer, safer, and easier to manage.</h2>
                        <p>
                            The platform collects only the information needed to operate account access, property discovery, booking workflows, payment tracking, roommate matching, communication, moderation, and support.
                        </p>
                    </div>

                    <aside class="privacy-summary-meta" aria-label="Privacy summary">
                        <span>Applies to</span>
                        <strong>Users, owners, roommate profiles, booking records, messages, reviews, and support requests.</strong>
                    </aside>
                </section>

                <section class="section-card section-pad privacy-list-card">
                    <ul class="privacy-list">
                        @foreach($privacySections as $section)
                            <li class="privacy-item">
                                <h2>{{ $section['title'] }}</h2>
                                <p>{{ $section['body'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="privacy-note">
                    <p>
                        This Privacy Policy is written for the FindNest Final Year Project context and should be read together with the Terms &amp; Conditions. For privacy questions, email support@findnest.com.
                    </p>
                </section>
            </div>
        @else
            <div class="faq-page">
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
            </div>
        @endif
    </main>

    @include('components.footer')
</body>
</html>
