<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $slug = $this->route('post');
        $post = \App\Models\Post::where('slug', $slug)->firstOrFail();
        return auth()->check() && auth()->user()->id === $post->user_id;
    }

    public function rules(): array
    {
        $slug = $this->route('post');
        $post = \App\Models\Post::where('slug', $slug)->firstOrFail();

        return [
            'title' => ['sometimes', 'string', 'min:5', 'max:255'],
            'body' => ['sometimes', 'string', 'min:100'],
            'slug' => ['nullable', Rule::unique('posts', 'slug')->ignore($post->id), 'regex:/^[a-z0-9-]+$/'],
            'category_ids' => ['sometimes', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'status' => ['sometimes', 'in:draft,published'],
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
            'title.min'      => 'Title must be at least 5 characters long.',
            'body.min'       => 'Post body must be at least 100 characters long.',
            'slug.regex'     => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique'    => 'This slug is already taken, please choose another.',
            'status.in'      => 'Status must be either draft or published.',
            'category_ids.array' => 'Categories must be a valid selection.',
        ];
    }
}
