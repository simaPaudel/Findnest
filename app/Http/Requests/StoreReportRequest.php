<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User can report any content except their own
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'reportable_type' => 'required|in:App\Models\Property,App\Models\Review,App\Models\User',
            'reportable_id' => 'required|integer|min:1',
            'report_type' => 'required|in:inappropriate_content,spam,harassment,fraud,fake_listing,scam,violence,discrimination,copyright,other',
            'reason' => 'required|string|min:10|max:500',
            'additional_info' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reportable_type.required' => 'The item type is required.',
            'reportable_type.in' => 'Invalid item type for reporting.',
            'reportable_id.required' => 'The item ID is required.',
            'reportable_id.integer' => 'The item ID must be a valid number.',
            'report_type.required' => 'Please select a report reason.',
            'report_type.in' => 'The selected report reason is invalid.',
            'reason.required' => 'A description is required.',
            'reason.min' => 'Please provide at least 10 characters in your report.',
            'reason.max' => 'Your report must not exceed 500 characters.',
            'additional_info.max' => 'Additional information must not exceed 2000 characters.',
        ];
    }

    /**
     * Get the data to be validated from the request.
     */
    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated($key, $default), [
            'reporter_id' => auth()->id(),
        ]);
    }
}
