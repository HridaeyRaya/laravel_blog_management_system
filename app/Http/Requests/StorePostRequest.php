<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string','min:5'],
            'body' => ['required','string', 'min:100'],
            'slug' => ['nullable', 'unique:posts,slug', 'regex:/^[a-z0-9-]+$/'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'title.min' => 'Title must be at least 5 characters long.',
            'title.required' => 'Please provide a title for your post.',
            'body.min' => 'Post body must be at least 100 characters long.',
            'slug.regex' => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'This slug is already taken, please choose another.',
            'category_ids.required' => 'Please select at least one category.',
            'status.in' => 'Status must be either draft or published.',
        ];
    }
}
