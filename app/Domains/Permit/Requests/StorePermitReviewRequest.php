<?php

declare(strict_types=1);

namespace App\Domains\Permit\Requests;

use App\Domains\Permit\State\PermitReview\Approved;
use App\Domains\Permit\State\PermitReview\Rejected;
use App\Domains\Permit\State\PermitReview\Revision;
use App\Domains\User\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermitReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(RoleEnum::Verificator->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permit_id' => ['required', 'integer', 'min:0', Rule::exists('permits', 'id')->withoutTrashed()],
            'state' => ['required', 'string', Rule::in([Approved::$name, Revision::$name, Rejected::$name])],
            'comment' => ['nullable', 'string'],
        ];
    }
}
