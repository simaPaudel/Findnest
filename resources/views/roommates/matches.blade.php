@extends('user.layout')

@section('title', 'Roommate Matches')
@section('page-title', 'Roommate Matches')

@section('content')
@php
    $matches = collect($matches ?? []);
    $roommateProfiles = [];

    $resolveProfilePhotoUrl = function ($path) {
        if (empty($path)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim(str_replace('\\', '/', (string) $path), '/');

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        return asset('images/user-placeholder.jpg');
    };
@endphp

<div class="space-y-6">
    <div class="fn-card p-6 md:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-500">Match Overview</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950">Your strongest roommate matches</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">
                    We filtered users by mutual gender compatibility and ranked them by budget, location, lifestyle, age, and shared interests.
                    Open a profile to review bio, email, preference choices, or start a conversation directly.
                </p>
            </div>

            <a href="{{ route('user.roommate-preferences.edit') }}" class="fn-btn-secondary shrink-0">
                Edit Preferences
            </a>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Budget</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $preference->budget_range ?: 'Not set' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Location</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $preference->preferred_location ?: 'Not set' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Gender Preference</p>
                <p class="mt-1 text-sm font-semibold capitalize text-slate-900">{{ $preference->gender_preference ?: 'Any' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Matches</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $matches->count() }} result{{ $matches->count() === 1 ? '' : 's' }}</p>
            </div>
        </div>
    </div>

    @if($matches->isEmpty())
        <div class="fn-card p-8 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="mt-4 text-xl font-semibold text-slate-950">No matches yet</h3>
            <p class="mt-2 text-sm leading-7 text-slate-500">
                Add a few more preference details or check back once more users complete their profiles.
            </p>
            <div class="mt-6">
                <a href="{{ route('user.roommate-preferences.edit') }}" class="fn-btn-primary">
                    Edit Preferences
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($matches as $match)
                @php
                    $preferences = $match['preferences'] ?? [];
                    $profileChoices = [
                        ['label' => 'Budget', 'value' => $preferences['budget_range'] ?? null],
                        ['label' => 'Location', 'value' => $preferences['preferred_location'] ?? null],
                        ['label' => 'Gender', 'value' => $preferences['gender_preference'] ?? null],
                        ['label' => 'Cleanliness', 'value' => $preferences['cleanliness_level'] ?? null],
                        ['label' => 'Sleep', 'value' => $preferences['sleep_schedule'] ?? null],
                        ['label' => 'Study', 'value' => $preferences['study_habits'] ?? null],
                        ['label' => 'Smoking', 'value' => $preferences['smoking_preference'] ?? null],
                        ['label' => 'Alcohol', 'value' => $preferences['alcohol_preference'] ?? null],
                        ['label' => 'Age', 'value' => !empty($preferences['age_range_min']) && !empty($preferences['age_range_max']) ? ($preferences['age_range_min'] . ' - ' . $preferences['age_range_max']) : null],
                        ['label' => 'Interests', 'value' => !empty($preferences['interests']) ? implode(', ', $preferences['interests']) : null],
                    ];
                    $score = (int) ($match['compatibility_score'] ?? 0);
                    $scoreClass = $score >= 80 ? 'fn-badge-green' : ($score >= 60 ? 'fn-badge-yellow' : 'fn-badge-red');
                    $photoUrl = $resolveProfilePhotoUrl($match['profile_photo'] ?? null);
                    $roommateProfile = [
                        'user_id' => $match['user_id'],
                        'name' => $match['name'],
                        'email' => $match['email'],
                        'bio' => $match['bio'] ?: 'No bio shared yet.',
                        'photo_url' => $photoUrl,
                        'score' => $score,
                        'reasons' => array_values($match['reasons'] ?? []),
                        'choices' => array_values(array_filter($profileChoices, static fn ($choice) => !empty($choice['value']))),
                    ];
                    $roommateProfiles[(string) $match['user_id']] = $roommateProfile;
                @endphp

                <div class="fn-card p-5 md:p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="flex min-w-0 items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-100 font-semibold text-slate-500">
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $match['name'] }}" class="h-full w-full object-cover">
                                @else
                                    <span>{{ strtoupper(substr($match['name'] ?? 'U', 0, 1)) }}</span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-semibold text-slate-950">{{ $match['name'] }}</h3>
                                    <span class="fn-badge {{ $scoreClass }}">{{ $score }}% Match</span>
                                </div>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $match['preferred_location'] ?: 'Location not specified' }}
                                    @if(!empty($match['budget_range']))
                                        <span class="mx-2 text-slate-300">&middot;</span>
                                        <span>{{ $match['budget_range'] }}</span>
                                    @endif
                                </p>

                                @if(!empty($match['reasons']))
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($match['reasons'] as $reason)
                                            <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                                                {{ $reason }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-rose-200 hover:text-rose-600"
                                data-open-profile-modal
                                data-roommate-user-id="{{ $match['user_id'] }}"
                            >
                                View Profile
                            </button>

                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-rose-500 bg-rose-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-600"
                                data-open-contact-modal
                                data-roommate-user-id="{{ $match['user_id'] }}"
                                data-contact-url="{{ route('user.conversations.roommate.create-or-open', ['userId' => $match['user_id']]) }}"
                            >
                                Contact
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="roommate-profile-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 py-6" style="display: none;" aria-hidden="true">
    <div class="w-full max-w-2xl rounded-[18px] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-500">View Profile</p>
                <h3 id="roommate-profile-name" class="mt-2 text-2xl font-bold text-slate-950"></h3>
                <p id="roommate-profile-email-top" class="mt-1 text-sm text-slate-500"></p>
            </div>
            <button type="button" class="rounded-full border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:text-slate-700" data-close-profile-modal>
                Close
            </button>
        </div>

        <div class="mt-6 flex flex-col gap-4 md:flex-row md:items-start">
            <div id="roommate-profile-avatar" class="h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-slate-100"></div>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span id="roommate-profile-score" class="fn-badge fn-badge-red">0% Match</span>
                </div>
                <p id="roommate-profile-bio" class="mt-3 text-sm leading-7 text-slate-600"></p>
                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Email</p>
                        <p id="roommate-profile-email" class="mt-1 break-words text-sm font-medium text-slate-900"></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Location</p>
                        <p id="roommate-profile-location" class="mt-1 text-sm font-medium text-slate-900"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Choices</p>
            <div id="roommate-profile-choices" class="mt-3 grid gap-2 sm:grid-cols-2"></div>
        </div>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Match Reasons</p>
            <div id="roommate-profile-reasons" class="mt-3 flex flex-wrap gap-2"></div>
        </div>
    </div>
</div>

<div id="roommate-contact-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 py-6" style="display: none;" aria-hidden="true">
    <div class="w-full max-w-xl rounded-[18px] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-500">Contact</p>
                <h3 id="roommate-contact-name" class="mt-2 text-2xl font-bold text-slate-950"></h3>
                <p id="roommate-contact-subtitle" class="mt-1 text-sm text-slate-500"></p>
            </div>
            <button type="button" class="rounded-full border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-500 transition hover:border-slate-300 hover:text-slate-700" data-close-contact-modal>
                Close
            </button>
        </div>

        <form class="mt-6 grid gap-3" data-roommate-contact-form>
            @csrf
            <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                <div id="roommate-contact-avatar" class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-white"></div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Message to send</p>
                    <p id="roommate-contact-email" class="mt-1 break-words text-sm text-slate-600"></p>
                </div>
            </div>

            <label for="roommate-contact-message" class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">
                Message
            </label>
            <textarea
                id="roommate-contact-message"
                name="message"
                rows="5"
                maxlength="5000"
                required
                placeholder="Write your first message..."
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-rose-500 focus:ring-2 focus:ring-rose-100"
            ></textarea>
            <p class="text-xs leading-6 text-slate-500">
                This opens a conversation and sends your first message.
            </p>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roommateProfiles = @json($roommateProfiles);
        const profileModal = document.getElementById('roommate-profile-modal');
        const contactModal = document.getElementById('roommate-contact-modal');
        const profileName = document.getElementById('roommate-profile-name');
        const profileEmailTop = document.getElementById('roommate-profile-email-top');
        const profileAvatar = document.getElementById('roommate-profile-avatar');
        const profileScore = document.getElementById('roommate-profile-score');
        const profileBio = document.getElementById('roommate-profile-bio');
        const profileEmail = document.getElementById('roommate-profile-email');
        const profileLocation = document.getElementById('roommate-profile-location');
        const profileChoices = document.getElementById('roommate-profile-choices');
        const profileReasons = document.getElementById('roommate-profile-reasons');

        const contactName = document.getElementById('roommate-contact-name');
        const contactSubtitle = document.getElementById('roommate-contact-subtitle');
        const contactAvatar = document.getElementById('roommate-contact-avatar');
        const contactEmail = document.getElementById('roommate-contact-email');
        const contactForm = contactModal?.querySelector('[data-roommate-contact-form]');
        const contactMessage = document.getElementById('roommate-contact-message');

        const renderAvatar = (container, photoUrl, name) => {
            if (!container) {
                return;
            }

            const initial = (name || 'U').trim().charAt(0).toUpperCase();
            container.innerHTML = photoUrl
                ? `<img src="${photoUrl}" alt="${escapeHtml(name || 'Profile')}" class="h-full w-full object-cover">`
                : `<div class="flex h-full w-full items-center justify-center text-lg font-bold text-slate-400">${escapeHtml(initial)}</div>`;
        };

        const escapeHtml = (value) => {
            const temp = document.createElement('div');
            temp.textContent = value ?? '';
            return temp.innerHTML;
        };

        const openModal = (modal) => {
            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = (modal) => {
            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        };

        const renderChipList = (container, values) => {
            if (!container) {
                return;
            }

            container.innerHTML = (values || [])
                .map((value) => `<span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">${escapeHtml(value)}</span>`)
                .join('');
        };

        const getProfileForButton = (button) => {
            const userId = button.getAttribute('data-roommate-user-id');
            if (!userId) {
                return null;
            }

            return roommateProfiles[userId] || roommateProfiles[String(userId)] || null;
        };

        const handleProfileOpen = (button) => {
            const profile = getProfileForButton(button);

            if (!profile) {
                return;
            }

            profileName.textContent = profile.name || 'Roommate profile';
            profileEmailTop.textContent = profile.email || '';
            profileBio.textContent = profile.bio || 'No bio shared yet.';
            profileEmail.textContent = profile.email || 'Not available';
            profileLocation.textContent = profile.choices?.find((choice) => choice.label === 'Location')?.value || 'Not set';
            profileScore.textContent = `${profile.score || 0}% Match`;
            profileScore.className = `fn-badge ${(profile.score || 0) >= 80 ? 'fn-badge-green' : ((profile.score || 0) >= 60 ? 'fn-badge-yellow' : 'fn-badge-red')}`;
            renderAvatar(profileAvatar, profile.photo_url || '', profile.name || 'U');

            const choiceItems = (profile.choices || []).map((choice) => {
                const value = Array.isArray(choice.value) ? choice.value.join(', ') : choice.value;
                return {
                    label: choice.label,
                    value: value || ''
                };
            }).filter((choice) => choice.value);

            profileChoices.innerHTML = choiceItems.map((choice) => `
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">${escapeHtml(choice.label)}</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">${escapeHtml(choice.value)}</p>
                </div>
            `).join('');

            renderChipList(profileReasons, profile.reasons || []);
            openModal(profileModal);
        };

        const handleContactOpen = (button) => {
            const profile = getProfileForButton(button);

            if (!profile || !contactForm) {
                return;
            }

            contactName.textContent = profile.name || 'Send message';
            contactSubtitle.textContent = 'Open a conversation and send a first message.';
            contactEmail.textContent = profile.email || 'No email available';
            renderAvatar(contactAvatar, profile.photo_url || '', profile.name || 'U');
            contactForm.dataset.contactUrl = button.getAttribute('data-contact-url') || '';
            contactForm.dataset.redirectUrl = @json(route('user.messages.index'));
            contactMessage.value = '';
            openModal(contactModal);
            window.setTimeout(() => contactMessage?.focus(), 50);
        };

        const handleCloseProfile = () => closeModal(profileModal);
        const handleCloseContact = () => closeModal(contactModal);

        document.addEventListener('click', (event) => {
            const profileButton = event.target.closest('[data-open-profile-modal]');
            if (profileButton) {
                handleProfileOpen(profileButton);
                return;
            }

            const contactButton = event.target.closest('[data-open-contact-modal]');
            if (contactButton) {
                handleContactOpen(contactButton);
                return;
            }

            const closeProfileButton = event.target.closest('[data-close-profile-modal]');
            if (closeProfileButton) {
                handleCloseProfile();
                return;
            }

            const closeContactButton = event.target.closest('[data-close-contact-modal]');
            if (closeContactButton) {
                handleCloseContact();
                return;
            }

            if (event.target === profileModal) {
                handleCloseProfile();
                return;
            }

            if (event.target === contactModal) {
                handleCloseContact();
            }
        });

        if (contactForm) {
            contactForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const message = (contactMessage?.value || '').trim();
                const submitButton = contactForm.querySelector('button[type="submit"]');
                const csrfToken = contactForm.querySelector('input[name="_token"]')?.value;
                const contactUrl = contactForm.dataset.contactUrl;
                const redirectUrl = contactForm.dataset.redirectUrl;

                if (!message || !contactUrl || !redirectUrl) {
                    contactMessage?.focus();
                    return;
                }

                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const openResponse = await fetch(contactUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken || '',
                        },
                    });

                    const openData = await openResponse.json();

                    if (!openResponse.ok) {
                        throw new Error(openData.message || 'Unable to open conversation.');
                    }

                    const conversationId = openData.conversation_id;
                    if (!conversationId) {
                        throw new Error('Conversation could not be created.');
                    }

                    const sendResponse = await fetch(`${window.location.origin}/user/conversations/${conversationId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken || '',
                        },
                        body: JSON.stringify({ message }),
                    });

                    const sendData = await sendResponse.json();

                    if (!sendResponse.ok) {
                        throw new Error(sendData.message || 'Unable to send message.');
                    }

                    window.location.href = `${redirectUrl}?conversation=${conversationId}`;
                } catch (error) {
                    alert(error.message || 'Unable to contact this roommate.');
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        }
    });
</script>
@endsection
