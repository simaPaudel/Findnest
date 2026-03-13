@extends('user.layout')

@section('title', 'Roommate Preferences')
@section('page-title', 'Roommate Preferences')

@section('content')
<div class="fn-glass-card p-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold fn-text-charcoal mb-2">Set Your Roommate Preferences</h2>
        <p class="fn-text-gray">Help us find your perfect roommate match by providing your preferences</p>
    </div>

    <form action="{{ route('user.roommate-preferences.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Budget Range -->
            <div>
                <label for="budget_range" class="block text-sm font-medium fn-text-charcoal mb-2">Budget Range</label>
                <input type="text" id="budget_range" name="budget_range" 
                       value="{{ old('budget_range', $preference->budget_range ?? '') }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="e.g., Rs 15,000 - Rs 25,000">
                @error('budget_range')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preferred Location -->
            <div>
                <label for="preferred_location" class="block text-sm font-medium fn-text-charcoal mb-2">Preferred Location</label>
                <input type="text" id="preferred_location" name="preferred_location" 
                       value="{{ old('preferred_location', $preference->preferred_location ?? '') }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="e.g., Kathmandu, Lalitpur">
                @error('preferred_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cleanliness Level -->
            <div>
                <label for="cleanliness_level" class="block text-sm font-medium fn-text-charcoal mb-2">Cleanliness Level</label>
                <select id="cleanliness_level" name="cleanliness_level" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Level</option>
                    <option value="very_clean" {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'very_clean' ? 'selected' : '' }}>Very Clean</option>
                    <option value="clean" {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'clean' ? 'selected' : '' }}>Clean</option>
                    <option value="moderate" {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="relaxed" {{ old('cleanliness_level', $preference->cleanliness_level ?? '') === 'relaxed' ? 'selected' : '' }}>Relaxed</option>
                </select>
                @error('cleanliness_level')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sleep Schedule -->
            <div>
                <label for="sleep_schedule" class="block text-sm font-medium fn-text-charcoal mb-2">Sleep Schedule</label>
                <select id="sleep_schedule" name="sleep_schedule" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Schedule</option>
                    <option value="early_bird" {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'early_bird' ? 'selected' : '' }}>Early Bird (Sleep early, wake early)</option>
                    <option value="night_owl" {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'night_owl' ? 'selected' : '' }}>Night Owl (Sleep late, wake late)</option>
                    <option value="flexible" {{ old('sleep_schedule', $preference->sleep_schedule ?? '') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                </select>
                @error('sleep_schedule')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Study Habits -->
            <div>
                <label for="study_habits" class="block text-sm font-medium fn-text-charcoal mb-2">Study Habits</label>
                <select id="study_habits" name="study_habits" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Habits</option>
                    <option value="quiet" {{ old('study_habits', $preference->study_habits ?? '') === 'quiet' ? 'selected' : '' }}>Quiet (Need silence to study)</option>
                    <option value="moderate" {{ old('study_habits', $preference->study_habits ?? '') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="social" {{ old('study_habits', $preference->study_habits ?? '') === 'social' ? 'selected' : '' }}>Social (Can study with noise)</option>
                </select>
                @error('study_habits')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gender Preference -->
            <div>
                <label for="gender_preference" class="block text-sm font-medium fn-text-charcoal mb-2">Gender Preference</label>
                <select id="gender_preference" name="gender_preference" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Preference</option>
                    <option value="male" {{ old('gender_preference', $preference->gender_preference ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender_preference', $preference->gender_preference ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="any" {{ old('gender_preference', $preference->gender_preference ?? '') === 'any' ? 'selected' : '' }}>Any</option>
                </select>
                @error('gender_preference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Smoking Preference -->
            <div>
                <label for="smoking_preference" class="block text-sm font-medium fn-text-charcoal mb-2">Smoking Preference</label>
                <select id="smoking_preference" name="smoking_preference" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Preference</option>
                    <option value="yes" {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'yes' ? 'selected' : '' }}>Smoking OK</option>
                    <option value="no" {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'no' ? 'selected' : '' }}>No Smoking</option>
                    <option value="outside_only" {{ old('smoking_preference', $preference->smoking_preference ?? '') === 'outside_only' ? 'selected' : '' }}>Outside Only</option>
                </select>
                @error('smoking_preference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alcohol Preference -->
            <div>
                <label for="alcohol_preference" class="block text-sm font-medium fn-text-charcoal mb-2">Alcohol Preference</label>
                <select id="alcohol_preference" name="alcohol_preference" 
                        class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition">
                    <option value="">Select Preference</option>
                    <option value="yes" {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'yes' ? 'selected' : '' }}>Drinking OK</option>
                    <option value="no" {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'no' ? 'selected' : '' }}>No Alcohol</option>
                    <option value="occasionally" {{ old('alcohol_preference', $preference->alcohol_preference ?? '') === 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                </select>
                @error('alcohol_preference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Max Roommates -->
            <div>
                <label for="max_roommates" class="block text-sm font-medium fn-text-charcoal mb-2">Max Roommates</label>
                <input type="number" id="max_roommates" name="max_roommates" min="1" max="10"
                       value="{{ old('max_roommates', $preference->max_roommates ?? '') }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="e.g., 2">
                @error('max_roommates')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Age Range Min -->
            <div>
                <label for="age_range_min" class="block text-sm font-medium fn-text-charcoal mb-2">Minimum Age</label>
                <input type="number" id="age_range_min" name="age_range_min" min="18" max="100"
                       value="{{ old('age_range_min', $preference->age_range_min ?? '') }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="e.g., 18">
                @error('age_range_min')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Age Range Max -->
            <div>
                <label for="age_range_max" class="block text-sm font-medium fn-text-charcoal mb-2">Maximum Age</label>
                <input type="number" id="age_range_max" name="age_range_max" min="18" max="100"
                       value="{{ old('age_range_max', $preference->age_range_max ?? '') }}" 
                       class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                       placeholder="e.g., 30">
                @error('age_range_max')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Interests -->
        <div class="mt-6">
            <label for="interests" class="block text-sm font-medium fn-text-charcoal mb-2">Interests & Hobbies</label>
            <textarea id="interests" name="interests" rows="3" 
                      class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                      placeholder="e.g., Reading, Gaming, Hiking, Music, Sports...">{{ old('interests', $preference->interests ?? '') }}</textarea>
            @error('interests')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs fn-text-gray mt-1">Maximum 500 characters</p>
        </div>

        <!-- Additional Preferences -->
        <div class="mt-6">
            <label for="additional_preferences" class="block text-sm font-medium fn-text-charcoal mb-2">Additional Preferences</label>
            <textarea id="additional_preferences" name="additional_preferences" rows="4" 
                      class="w-full px-4 py-3 border fn-border-gray rounded-xl focus:outline-none focus:fn-border-red transition"
                      placeholder="Any other preferences or requirements...">{{ old('additional_preferences', $preference->additional_preferences ?? '') }}</textarea>
            @error('additional_preferences')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs fn-text-gray mt-1">Maximum 1000 characters</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="fn-btn-primary">
                Save Preferences
            </button>
            <a href="{{ route('user.dashboard') }}" class="fn-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
