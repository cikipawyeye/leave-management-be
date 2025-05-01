<?php

declare(strict_types=1);

namespace App\Domains\User\Requests;

use App\Domains\User\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $emailRule = Rule::unique('users', 'email')->withoutTrashed();

        if ($this->route('user')) {
            $emailRule->ignore($this->route('user'), 'id');
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                $emailRule,
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:255', Rule::in([
                RoleEnum::Verificator->value,
                RoleEnum::User->value,
            ])],
            'should_verify_email' => ['nullable', 'boolean'],
        ];
    }
}
