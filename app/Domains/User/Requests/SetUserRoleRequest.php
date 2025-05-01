<?php

declare(strict_types=1);

namespace App\Domains\User\Requests;

use App\Domains\User\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetUserRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', 'max:255', Rule::in([
                RoleEnum::Verificator->value,
                RoleEnum::User->value,
            ])],
        ];
    }
}
