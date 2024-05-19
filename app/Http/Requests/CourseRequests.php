<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'       => ['required', 'integer'],
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            // cover with png or jpg or jpeg format
            'cover'             => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'published_at'      => ['nullable', 'date'],
            'status'            => ['nullable', 'boolean'],
            'agree_tnc'         => ['nullable', 'boolean'],
            'passing_grade'     => ['nullable', 'integer']
        ];
    }
}
