<?php

declare(strict_types=1);

namespace App\Domains\Permit\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePermitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * Edit Permit.
         * Check whether the permit is belonging to the user
         */
        if (! empty($this->route('permit'))) {
            return $this->user()->id == $this->route('permit')->user_id;
        }

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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:sick,leave,other',
        ];
    }
}
