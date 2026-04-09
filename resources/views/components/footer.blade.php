<!-- Reusable Footer Component -->
<style>
    .fn-footer {
        background-color: #1f2937;
        border-top: 1px solid #374151;
        padding: 48px 0 28px;
    }

    .fn-footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .fn-footer-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) repeat(3, minmax(0, 1fr));
        gap: 40px;
        align-items: start;
        margin-bottom: 32px;
    }

    .fn-footer-section h4 {
        margin-bottom: 16px;
        color: #f8fafc;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
    }

    .fn-footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        color: #ff385c;
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .fn-footer-brand svg {
        width: 22px;
        height: 22px;
    }

    .fn-footer-description {
        max-width: 280px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .fn-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .fn-footer-links li + li {
        margin-top: 12px;
    }

    .fn-footer-links a {
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
        font-size: 0.92rem;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .fn-footer-links a:hover {
        color: #ffffff;
        transform: translateX(2px);
    }

    .fn-footer-socials {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fn-footer-social-link {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        text-decoration: none;
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }

    .fn-footer-social-link:hover {
        transform: translateY(-2px);
        background: #ff385c;
        border-color: #ff385c;
    }

    .fn-footer-social-link svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }

    .fn-footer-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 20px;
        text-align: center;
    }

    .fn-footer-copyright {
        color: rgba(255, 255, 255, 0.58);
        font-size: 0.85rem;
        line-height: 1.7;
    }

    @media (max-width: 1024px) {
        .fn-footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 32px;
        }

        .fn-footer-description {
            max-width: none;
        }
    }

    @media (max-width: 640px) {
        .fn-footer {
            padding: 38px 0 24px;
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    FindNest
                </div>
                <p class="fn-footer-description">
                    Your trusted platform for accommodation and roommate matching.
                </p>
            </div>

            <div class="fn-footer-section">
                <h4>Quick Links</h4>
                <ul class="fn-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('listings.index') }}">Browse Listings</a></li>
                    <li><a href="{{ route('pages.about') }}">About Us</a></li>
                    <li><a href="{{ route('pages.faq') }}">FAQ</a></li>
                </ul>
            </div>

            <div class="fn-footer-section">
                <h4>Support</h4>
                <ul class="fn-footer-links">
                    <li><a href="{{ route('pages.help-center') }}">Help Center</a></li>
                    <li><a href="{{ route('pages.faq') }}">FAQ</a></li>
                    <li><a href="{{ route('pages.about') }}">About Us</a></li>
                    <li><a href="{{ route('roommates.index') }}">Find Roommates</a></li>
                </ul>
            </div>

            <div class="fn-footer-section">
                <h4>Connect</h4>
                <div class="fn-footer-socials">
                    <a href="#" class="fn-footer-social-link" title="Facebook">
                        <svg viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="fn-footer-social-link" title="Twitter">
                        <svg viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417a9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a href="#" class="fn-footer-social-link" title="Instagram">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="fn-footer-divider">
            <p class="fn-footer-copyright">
                &copy; 2026 FindNest. All rights reserved. Your trusted housing partner.
            </p>
        </div>
    </div>
</footer>