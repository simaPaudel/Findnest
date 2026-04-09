@extends('user.layout')

@section('title', 'Roommate Preferences')
@section('page-title', 'Roommate Preferences')

@section('content')
<style>
    /* Preference Chips - Modern Toggle Style */
    .preference-chip {
        display: inline-block;
        padding: 11px 18px;
        margin: 0 8px 8px 0;
        background: var(--fn-white);
        border: 1.5px solid #e5e5e5;
        border-radius: 24px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
    }

    .preference-chip:hover {
        border-color: #ff385c;
        background: rgba(255, 56, 92, 0.08);
        color: var(--fn-charcoal);
        transform: translateY(-1px);
    }

    .preference-chip.selected {
        background: #ff385c;
        color: var(--fn-white);
        border-color: #ff385c;
        box-shadow: 0 2px 8px rgba(255, 56, 92, 0.25);
    }

    /* Section Cards - Refined */
    .preference-section {
        background: var(--fn-white);
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 36px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .preference-section:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-color: #e0e0e0;
    }

    /* Section Title */
    .section-title {
        font-size: 1.1rem;
        font-weight: 650;
        color: #1a1a1a;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .section-subtitle {
        font-size: 0.85rem;
        color: #8b92a0;
        display: block;
        margin-bottom: 24px;
        font-weight: 400;
    }

    /* Form Fields */
    .form-field {
        margin-bottom: 24px;
    }

    .form-field:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
        letter-spacing: -0.2px;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: #1a1a1a;
        background: var(--fn-white);
        transition: all 0.2s ease;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #b0b8c1;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #ff385c;
        box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
    }

    .form-textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.5;
    }

    .form-helper {
        font-size: 0.8rem;
        color: #8b92a0;
        margin-top: 6px;
    }

    .form-error {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 6px;
        font-weight: 500;
    }

    /* Grid Layout */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .chips-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        margin-bottom: 4px;
    }

    /* Header */
    .header-section {
        margin-bottom: 40px;
    }

    .header-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
        letter-spacing: -0.8px;
    }

    .header-subtitle {
        font-size: 0.95rem;
        color: #6b75a0;
        line-height: 1.6;
        font-weight: 400;
    }

    .sections-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 28px;
        margin-bottom: 40px;
    }

    /* Main Container */
    .preference-container-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Buttons */
    .button-group {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 48px;
        padding-top: 32px;
        border-top: 1px solid #e5e5e5;
    }

    .btn-primary {
        background: #ff385c;
        color: var(--fn-white);
        padding: 13px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(255, 56, 92, 0.2);
    }

    .btn-primary:hover {
        background: #e11d48;
        box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
        transform: translateY(-1px);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: transparent;
        color: #4b5563;
        padding: 13px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1.5px solid #e5e5e5;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        border-color: #ff385c;
        color: #ff385c;
        background: rgba(255, 56, 92, 0.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .header-title {
            font-size: 1.75rem;
        }

        .preference-section {
            padding: 24px;
        }

        .button-group {
            flex-direction: column;
            gap: 10px;
            margin-top: 36px;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .preference-chip {
            padding: 10px 16px;
            margin: 0 6px 6px 0;
        }
    }
</style>

<div class="preference-container-wrapper">
    <!-- Header -->
    <div class="header-section">
        <p class="header-subtitle">Tell us what you're looking for in a roommate. These preferences help us find the perfect match for you.</p>
    </div>

    <form action="{{ route('user.roommate-preferences.store') }}" method="POST">
        @csrf

        <div class="sections-container">
            <!-- SECTION 1: BASIC PREFERENCES -->
            <div class="preference-section">
                <h2 class="section-title">
                    Basic Preferences
                    <span class="section-subtitle">Budget, location, and household size</span>
                </h2>

                <div class="form-row">
                    <!-- Budget Range -->
                    <div class="form-field">
                        <label for="budget_range" class="form-label">Budget Range</label>
                        <input type="text" id="budget_range" name="budget_range"
                            value="{{ old('budget_range', $preference->budget_range ?? '') }}"
                            class="form-input"
                            placeholder="e.g., Rs 15,000 - Rs 25,000">
                        @error('budget_range')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Location -->
                    <div class="form-field">
                        <label for="preferred_location" class="form-label">Preferred Location</label>
                        <input type="text" id="preferred_location" name="preferred_location"
                            value="{{ old('preferred_location', $preference->preferred_location ?? '') }}"
                            class="form-input"
                            placeholder="e.g., Kathmandu, Lalitpur">
                        @error('preferred_location')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <!-- Age Range Min -->
                    <div class="form-field">
                        <label for="age_range_min" class="form-label">Age Range — From</label>
                        <input type="number" id="age_range_min" name="age_range_min" min="18" max="100"
                            value="{{ old('age_range_min', $preference->age_range_min ?? '') }}"
                            class="form-input"
                            placeholder="18">
                        @error('age_range_min')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Age Range Max -->
                    <div class="form-field">
                        <label for="age_range_max" class="form-label">Age Range — To</label>
                        <input type="number" id="age_range_max" name="age_range_max" min="18" max="100"
                            value="{{ old('age_range_max', $preference->age_range_max ?? '') }}"
                            class="form-input"
                            placeholder="30">
                        @error('age_range_max')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-field">
                    <label for="max_roommates" class="form-label">Maximum Roommates</label>
                    <input type="number" id="max_roommates" name="max_roommates" min="1" max="10"
                        value="{{ old('max_roommates', $preference->max_roommates ?? '') }}"
                        class="form-input"
                        placeholder="2">
                    @error('max_roommates')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SECTION 2: LIFESTYLE -->
            <div class="preference-section">
                <h2 class="section-title">
                    Lifestyle
                    <span class="section-subtitle">Your daily habits and preferences</span>
                </h2>

                <!-- Cleanliness Level -->
                <div class="form-field">
                    <label class="form-label">Cleanliness Level</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'very_clean' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'cleanliness_level', 'very_clean')">Very Clean</button>
                        <button type="button" class="preference-chip {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'clean' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'cleanliness_level', 'clean')">Clean</button>
                        <button type="button" class="preference-chip {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'moderate' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'cleanliness_level', 'moderate')">Moderate</button>
                        <button type="button" class="preference-chip {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'relaxed' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'cleanliness_level', 'relaxed')">Relaxed</button>
                    </div>
                    <input type="hidden" id="cleanliness_level" name="cleanliness_level" value="{{ old('cleanliness_level', $preference->cleanliness_level ?? '') }}">
                    @error('cleanliness_level')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sleep Schedule -->
                <div class="form-field">
                    <label class="form-label">Sleep Schedule</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'early_bird' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'sleep_schedule', 'early_bird')">Early Bird</button>
                        <button type="button" class="preference-chip {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'night_owl' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'sleep_schedule', 'night_owl')">Night Owl</button>
                        <button type="button" class="preference-chip {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'flexible' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'sleep_schedule', 'flexible')">Flexible</button>
                    </div>
                    <input type="hidden" id="sleep_schedule" name="sleep_schedule" value="{{ old('sleep_schedule', $preference->sleep_schedule ?? '') }}">
                    @error('sleep_schedule')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Study Habits -->
                <div class="form-field">
                    <label class="form-label">Study Habits</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('study_habits', $preference->study_habits ?? '') === 'quiet' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'study_habits', 'quiet')">Quiet</button>
                        <button type="button" class="preference-chip {{ old('study_habits', $preference->study_habits ?? '') === 'moderate' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'study_habits', 'moderate')">Moderate</button>
                        <button type="button" class="preference-chip {{ old('study_habits', $preference->study_habits ?? '') === 'social' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'study_habits', 'social')">Social</button>
                    </div>
                    <input type="hidden" id="study_habits" name="study_habits" value="{{ old('study_habits', $preference->study_habits ?? '') }}">
                    @error('study_habits')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Smoking Preference -->
                <div class="form-field">
                    <label class="form-label">Smoking</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'yes' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'smoking_preference', 'yes')">OK with it</button>
                        <button type="button" class="preference-chip {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'no' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'smoking_preference', 'no')">Prefer not</button>
                        <button type="button" class="preference-chip {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'outside_only' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'smoking_preference', 'outside_only')">Outside only</button>
                    </div>
                    <input type="hidden" id="smoking_preference" name="smoking_preference" value="{{ old('smoking_preference', $preference->smoking_preference ?? '') }}">
                    @error('smoking_preference')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alcohol Preference -->
                <div class="form-field">
                    <label class="form-label">Alcohol</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'yes' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'alcohol_preference', 'yes')">OK with it</button>
                        <button type="button" class="preference-chip {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'no' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'alcohol_preference', 'no')">Prefer not</button>
                        <button type="button" class="preference-chip {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'occasionally' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'alcohol_preference', 'occasionally')">Occasionally</button>
                    </div>
                    <input type="hidden" id="alcohol_preference" name="alcohol_preference" value="{{ old('alcohol_preference', $preference->alcohol_preference ?? '') }}">
                    @error('alcohol_preference')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SECTION 3: ABOUT YOU -->
            <div class="preference-section">
                <h2 class="section-title">
                    About You
                    <span class="section-subtitle">Let them know more about yourself</span>
                </h2>

                <!-- Gender Preference -->
                <div class="form-field">
                    <label class="form-label">Gender Preference</label>
                    <div class="chips-container">
                        <button type="button" class="preference-chip {{ old('gender_preference', $preference->gender_preference ?? '') === 'male' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'gender_preference', 'male')">Male</button>
                        <button type="button" class="preference-chip {{ old('gender_preference', $preference->gender_preference ?? '') === 'female' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'gender_preference', 'female')">Female</button>
                        <button type="button" class="preference-chip {{ old('gender_preference', $preference->gender_preference ?? '') === 'any' ? 'selected' : '' }}"
                            onclick="selectChip(this, 'gender_preference', 'any')">No Preference</button>
                    </div>
                    <input type="hidden" id="gender_preference" name="gender_preference" value="{{ old('gender_preference', $preference->gender_preference ?? '') }}">
                    @error('gender_preference')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Interests -->
                <div class="form-field">
                    <label for="interests" class="form-label">Interests & Hobbies</label>
                    <textarea id="interests" name="interests" class="form-textarea"
                        placeholder="e.g., Reading, Gaming, Hiking, Music, Sports...">{{ old('interests', $preference->interests ?? '') }}</textarea>
                    <p class="form-helper">Maximum 500 characters</p>
                    @error('interests')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Preferences -->
                <div class="form-field">
                    <label for="additional_preferences" class="form-label">Additional Information</label>
                    <textarea id="additional_preferences" name="additional_preferences" class="form-textarea"
                        placeholder="Any other preferences, house rules, or things your roommate should know...">{{ old('additional_preferences', $preference->additional_preferences ?? '') }}</textarea>
                    <p class="form-helper">Maximum 1000 characters</p>
                    @error('additional_preferences')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-primary">
                Save & Find Matches
            </button>
            <a href="{{ route('user.dashboard') }}" class="btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function selectChip(chipElement, fieldName, value) {
        event.preventDefault();

        // Remove selected state from all sibling chips
        const parent = chipElement.parentElement;
        const siblings = parent.querySelectorAll('.preference-chip');
        siblings.forEach(chip => {
            chip.classList.remove('selected');
        });

        // Add selected state to clicked chip
        chipElement.classList.add('selected');

        // Update hidden input value
        document.getElementById(fieldName).value = value;
    }
</script>

@endsection