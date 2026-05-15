<!-- Reusable Footer Component -->
<style>
    .fn-footer {
        background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        padding: 36px 0 16px;
        color: #f8fafc;
    }

    .fn-footer-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 18px;
    }

    .fn-footer-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) repeat(3, minmax(0, 0.86fr));
        gap: 22px;
        align-items: start;
    }

    .fn-footer-brand {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #ffffff;
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .fn-footer-brand-mark {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        border: 1px solid rgba(255, 56, 92, 0.28);
        background: rgba(255, 56, 92, 0.14);
        color: #ff385c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .fn-footer-brand-mark svg {
        width: 16px;
        height: 16px;
    }

    .fn-footer-description {
        max-width: 240px;
        color: rgba(226, 232, 240, 0.72);
        font-size: 0.76rem;
        line-height: 1.45;
    }

    .fn-footer-section {
        min-width: 0;
    }

    .fn-footer-heading {
        margin-bottom: 10px;
        color: #ffffff;
        font-size: 0.64rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
    }

    .fn-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 8px;
    }

    .fn-footer-links a,
    .fn-footer-contact-link {
        color: rgba(226, 232, 240, 0.72);
        text-decoration: none;
        font-size: 0.78rem;
        line-height: 1.4;
        transition: color 0.2s ease, padding-left 0.2s ease;
    }

    .fn-footer-links a:hover,
    .fn-footer-contact-link:hover {
        color: #ffffff;
        padding-left: 4px;
    }

    .fn-footer-contact {
        display: grid;
        gap: 10px;
    }

    .fn-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        color: rgba(226, 232, 240, 0.72);
        font-size: 0.78rem;
        line-height: 1.4;
    }

    .fn-footer-contact-icon {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: rgba(255, 255, 255, 0.05);
        color: #ff9fb0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .fn-footer-contact-icon svg {
        width: 13px;
        height: 13px;
    }

    .fn-footer-contact-label {
        display: block;
        margin-bottom: 2px;
        color: #ffffff;
        font-size: 0.62rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .fn-footer-bottom {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid rgba(148, 163, 184, 0.16);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fn-footer-copyright {
        color: rgba(226, 232, 240, 0.78);
        font-size: 0.76rem;
        line-height: 1.4;
        text-align: left;
    }

    .fn-footer-note {
        margin-top: 4px;
        color: rgba(148, 163, 184, 0.8);
        font-size: 0.7rem;
        line-height: 1.35;
        text-align: left;
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
            padding: 30px 0 16px;
        }

        .fn-footer-container {
            padding: 0 20px;
        }

        .fn-footer-grid {
            grid-template-columns: 1fr;
            gap: 22px;
        }

        .fn-footer-grid > div:first-child,
        .fn-footer-grid > .fn-footer-section:last-child {
            grid-column: auto;
        }

        .fn-footer-section {
            padding-top: 18px;
            border-top: 1px solid rgba(148, 163, 184, 0.12);
        }

        .fn-footer-brand {
            margin-bottom: 8px;
        }

        .fn-footer-description {
            max-width: none;
            font-size: 0.78rem;
            line-height: 1.6;
        }

        .fn-footer-heading {
            margin-bottom: 10px;
            font-size: 0.64rem;
            letter-spacing: 0.12em;
        }

        .fn-footer-links {
            gap: 9px;
        }

        .fn-footer-links a,
        .fn-footer-contact-link,
        .fn-footer-contact-item {
            font-size: 0.78rem;
            line-height: 1.5;
        }

        .fn-footer-links a {
            display: inline-flex;
            padding: 2px 0;
        }

        .fn-footer-contact {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .fn-footer-contact-item {
            gap: 10px;
            align-items: flex-start;
        }

        .fn-footer-contact-icon {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            margin-top: 1px;
        }

        .fn-footer-bottom {
            margin-top: 24px;
            padding-top: 14px;
            gap: 8px;
            align-items: flex-start;
            flex-direction: column;
        }

        .fn-footer-copyright,
        .fn-footer-note {
            font-size: 0.72rem;
            line-height: 1.5;
        }
    }

    @media (max-width: 360px) {
        .fn-footer-grid {
            gap: 20px;
        }

        .fn-footer-container {
            padding: 0 16px;
        }
    }
</style>

<footer class="fn-footer">
    <div class="fn-footer-container">
        <div class="fn-footer-grid">
            <div>
                <div class="fn-footer-brand">
                    <?php if (isset($component)) { $__componentOriginal343e84183e8c00ed9639e7134ef5492a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal343e84183e8c00ed9639e7134ef5492a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.findnest-logo','data' => ['variant' => 'inline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('findnest-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'inline','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal343e84183e8c00ed9639e7134ef5492a)): ?>
<?php $attributes = $__attributesOriginal343e84183e8c00ed9639e7134ef5492a; ?>
<?php unset($__attributesOriginal343e84183e8c00ed9639e7134ef5492a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal343e84183e8c00ed9639e7134ef5492a)): ?>
<?php $component = $__componentOriginal343e84183e8c00ed9639e7134ef5492a; ?>
<?php unset($__componentOriginal343e84183e8c00ed9639e7134ef5492a); ?>
<?php endif; ?>
                </div>
                <p class="fn-footer-description">
                    Verified stays and roommate matching.
                </p>
            </div>

            <nav class="fn-footer-section" aria-label="Quick Links">
                <h4 class="fn-footer-heading">Quick Links</h4>
                <ul class="fn-footer-links">
                    <li><a href="<?php echo e(route('pages.about')); ?>">About Us</a></li>
                    <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li><a href="<?php echo e(route('listings.index')); ?>">Browse Listings</a></li>
                    <li><a href="<?php echo e(route('login')); ?>">Find Roommates</a></li>
                    <li><a href="<?php echo e(route('home')); ?>#how-it-works">How It Works</a></li>
                </ul>
            </nav>

            <nav class="fn-footer-section" aria-label="Support">
                <h4 class="fn-footer-heading">Support</h4>
                <ul class="fn-footer-links">
                    <li><a href="<?php echo e(route('pages.help-center')); ?>">Help Center</a></li>
                    <li><a href="<?php echo e(route('pages.faq')); ?>">FAQ</a></li>
                    <li><a href="<?php echo e(route('pages.contact')); ?>">Contact Us</a></li>
                    <li><a href="<?php echo e(route('pages.terms')); ?>">Terms &amp; Conditions</a></li>
                    <li><a href="<?php echo e(route('pages.privacy')); ?>">Privacy Policy</a></li>
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
                Built for clearer housing search and roommate discovery.
            </p>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/components/footer.blade.php ENDPATH**/ ?>