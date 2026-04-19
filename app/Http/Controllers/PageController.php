<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.info', [
            'pageTitle' => 'About Us',
            'eyebrow' => 'About FindNest',
            'heading' => 'A simpler way to find rooms, homes, and the right living setup.',
            'intro' => 'FindNest helps renters discover verified listings, compare room options clearly, and move forward with more confidence.',
            'highlights' => [
                ['label' => 'Verified listings', 'value' => 'Trusted owner-first rental flow'],
                ['label' => 'Room + property options', 'value' => 'Browse full homes or individual rooms'],
                ['label' => 'Safer booking journey', 'value' => 'Clear booking, payment, and review steps'],
            ],
            'sections' => [
                [
                    'title' => 'What FindNest is built for',
                    'body' => [
                        'We designed FindNest for people who want a cleaner rental experience without guessing which listing is real, which room is still available, or how booking works.',
                        'The platform supports both full-property rentals and shared-living room rentals, so users can choose the setup that actually matches their budget and lifestyle.',
                    ],
                ],
                [
                    'title' => 'How we keep the flow practical',
                    'body' => [
                        'Owners can create structured listings with room details, pricing, and images. Users can compare options, request bookings, and review their stay after payment and confirmation.',
                        'Reviews and listing approvals are moderated to keep the marketplace more trustworthy and easier to scan.',
                    ],
                ],
                [
                    'title' => 'What matters to us',
                    'body' => [
                        'Clarity, trust, and usability. We focus on making housing discovery feel less noisy and more reliable for both renters and property owners.',
                    ],
                ],
            ],
        ]);
    }

    public function contact(): View
    {
        return view('pages.info', [
            'pageTitle' => 'Contact Us',
            'eyebrow' => 'Contact Us',
            'heading' => 'Get in touch with the FindNest team.',
            'intro' => 'Use the details below for booking issues, account questions, or general support. We keep contact simple and easy to follow.',
            'contactCards' => [
                [
                    'title' => 'Email',
                    'value' => 'support@findnest.com',
                    'note' => 'Best for booking, account, and listing questions.',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 6 8 6 8-6"></path></svg>',
                ],
                [
                    'title' => 'Location',
                    'value' => 'Pokhara, Nepal',
                    'note' => 'FindNest is based in Pokhara and supports users from there and beyond.',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"></path><circle cx="12" cy="11" r="2.5" fill="currentColor" stroke="none"></circle></svg>',
                ],
                [
                    'title' => 'Response time',
                    'value' => '1 to 2 business days',
                    'note' => 'We reply as soon as possible during working days.',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v6l4 2"></path></svg>',
                ],
            ],
            'sections' => [
                [
                    'title' => 'What to include in your message',
                    'body' => [
                        'If your message is about a booking or payment, include the booking ID, property title, and the email used on the account so the team can trace it faster.',
                    ],
                ],
            ],
        ]);
    }

    public function faq(): View
    {
        return view('pages.info', [
            'pageTitle' => 'FAQ',
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'Answers to the questions users ask most often.',
            'intro' => 'These are the basics of how FindNest listings, room bookings, payments, and reviews work.',
            'faqItems' => [
                [
                    'question' => 'Can I book a single room instead of the full property?',
                    'answer' => 'Yes. If a property is listed in per-room mode, you can choose a specific room from the property details page and continue booking from there.',
                ],
                [
                    'question' => 'When does a room become unavailable?',
                    'answer' => 'A room is locked once a booking is successfully paid and confirmed. When the stay ends and an admin releases the booking, the room becomes available again.',
                ],
                [
                    'question' => 'Do reviews show immediately after submission?',
                    'answer' => 'No. Users can submit a review after a valid booking, but the review only appears publicly after admin approval.',
                ],
                [
                    'question' => 'How much does the user pay during booking?',
                    'answer' => 'The current booking flow collects a 20% advance payment for the first month. The remaining balance is still shown clearly on the invoice and booking pages.',
                ],
                [
                    'question' => 'Can I save properties for later?',
                    'answer' => 'Yes. Signed-in users can save listings and revisit them from the saved listings page.',
                ],
                [
                    'question' => 'Can owners manage room-level availability?',
                    'answer' => 'Yes. Owners can control room availability, and a confirmed paid booking locks that room until an admin releases it after the stay ends.',
                ],
            ],
        ]);
    }

    public function helpCenter(): View
    {
        return view('pages.info', [
            'pageTitle' => 'Help Center',
            'eyebrow' => 'Help Center',
            'heading' => 'Get guidance for bookings, listings, payments, and account questions.',
            'intro' => 'Use this page as the starting point when you need support with your FindNest account or a booking-related issue.',
            'supportCards' => [
                [
                    'title' => 'Booking help',
                    'text' => 'Need help with availability, booking status, invoices, or release timing? Start by checking your booking details page and payment history.',
                ],
                [
                    'title' => 'Listing help',
                    'text' => 'Owners can manage room details, pricing, images, and property status from the owner dashboard. Listing approval and verification are handled separately.',
                ],
                [
                    'title' => 'Account help',
                    'text' => 'If you need help with login, profile details, or saved listings, review your account pages first and then reach out if the issue remains.',
                ],
            ],
            'sections' => [
                [
                    'title' => 'Before you reach out',
                    'body' => [
                        'Check whether the issue is already visible in your dashboard, booking page, or invoice. Many status updates such as pending review, payment status, and confirmed stays are already shown there.',
                    ],
                    'bullets' => [
                        'Review the booking or listing page where the issue happened',
                        'Confirm the payment status before retrying another payment',
                        'Check whether the listing or review is waiting for admin approval',
                    ],
                ],
                [
                    'title' => 'Need more help?',
                    'body' => [
                        'If you still need help, contact the FindNest support team with your booking ID or property title so the issue can be traced faster.',
                    ],
                    'meta' => [
                        ['label' => 'Support email', 'value' => 'support@findnest.com'],
                        ['label' => 'Response window', 'value' => 'Usually within 1 to 2 business days'],
                    ],
                ],
            ],
        ]);
    }
}
