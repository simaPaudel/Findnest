<!-- Reusable Footer Component -->
<style>
    .fn-footer {
        background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        padding: 72px 0 28px;
        color: #f8fafc;
    }

    .fn-footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .fn-footer-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) repeat(3, minmax(0, 1fr));
        gap: 36px;
        align-items: start;
    }

    .fn-footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .fn-footer-brand-mark {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        border: 1px solid rgba(255, 56, 92, 0.28);
        background: rgba(255, 56, 92, 0.14);
        color: #ff385c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .fn-footer-brand-mark svg {
        width: 20px;
        height: 20px;
    }

    .fn-footer-description {
        max-width: 360px;
        color: rgba(226, 232, 240, 0.72);
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .fn-footer-section {
        min-width: 0;
    }

    .fn-footer-heading {
        margin-bottom: 18px;
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.18em;
    }

    .fn-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 12px;
    }

    .fn-footer-links a,
    .fn-footer-contact-link {
        color: rgba(226, 232, 240, 0.72);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.2s ease, padding-left 0.2s ease;
    }

    .fn-footer-links a:hover,
    .fn-footer-contact-link:hover {
        color: #ffffff;
        padding-left: 4px;
    }

    .fn-footer-contact {
        display: grid;
        gap: 14px;
    }

    .fn-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: rgba(226, 232, 240, 0.72);
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .fn-footer-contact-icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: rgba(255, 255, 255, 0.05);
        color: #ff9fb0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .fn-footer-contact-icon svg {
        width: 16px;
        height: 16px;
    }

    .fn-footer-contact-label {
        display: block;
        margin-bottom: 4px;
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .fn-footer-bottom {
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid rgba(148, 163, 184, 0.16);
        text-align: center;
    }

    .fn-footer-copyright {
        color: rgba(226, 232, 240, 0.78);
        font-size: 0.88rem;
        line-height: 1.7;
    }

    .fn-footer-note {
        margin-top: 6px;
        color: rgba(148, 163, 184, 0.8);
        font-size: 0.84rem;
    }

    @media (max-width: 1024px) {
        .fn-footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .fn-footer-description {
            max-width: none;
        }

    }

    @media (max-width: 640px) {
        .fn-footer {
            padding: 56px 0 24px;
        }

        .fn-footer-container {
            padding: 0 18px;
        }

        .fn-footer-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }

    }
</style>

<footer class="fn-footer">
    <div class="fn-footer-container">
        <div class="fn-footer-grid">
            <div>
                <div class="fn-footer-brand">
                    <x-findnest-logo variant="stacked" size="md" />
                </div>
                <p class="fn-footer-description">
                    A trusted platform for secure accommodation booking and roommate matching.
                </p>
            </div>

            <nav class="fn-footer-section" aria-label="Quick Links">
                <h4 class="fn-footer-heading">Quick Links</h4>
                <ul class="fn-footer-links">
                    <li><a href="{{ route('pages.about') }}">About Us</a></li>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('listings.index') }}">Browse Listings</a></li>
                    <li><a href="{{ route('login') }}">Find Roommates</a></li>
                    <li><a href="{{ route('home') }}#how-it-works">How It Works</a></li>
                </ul>
            </nav>

            <nav class="fn-footer-section" aria-label="Support">
                <h4 class="fn-footer-heading">Support</h4>
                <ul class="fn-footer-links">
                    <li><a href="{{ route('pages.help-center') }}">Help Center</a></li>
                    <li><a href="{{ route('pages.faq') }}">FAQ</a></li>
                    <li><a href="{{ route('pages.contact') }}">Contact Us</a></li>
                </ul>
            </nav>

            <div class="fn-footer-section">
                <h4 class="fn-footer-heading">Contact Information</h4>
                <div class="fn-footer-contact">
                    <div class="fn-footer-contact-item">
                        <span class="fn-footer-contact-icon" aria-hidden="true">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 6 8 6 8-6"></path>
                            </svg>
                        </span>
                        <div>
                            <span class="fn-footer-contact-label">Email</span>
                            <a class="fn-footer-contact-link" href="mailto:support@findnest.com">support@findnest.com</a>
                        </div>
                    </div>

                    <div class="fn-footer-contact-item">
                        <span class="fn-footer-contact-icon" aria-hidden="true">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"></path>
                                <circle cx="12" cy="11" r="2.5" fill="currentColor" stroke="none"></circle>
                            </svg>
                        </span>
                        <div>
                            <span class="fn-footer-contact-label">Location</span>
                            <span>Pokhara, Nepal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fn-footer-bottom">
            <p class="fn-footer-copyright">
                &copy; 2026 FindNest. All rights reserved.
            </p>
            <p class="fn-footer-note">
                Designed for smarter housing and roommate discovery.
            </p>
        </div>
    </div>
</footer>
