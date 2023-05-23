<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        return $user->can('update personal information');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required'],
            'weight' => ['required'],
            'height' => ['required'],
            'other_information' => ['sometimes', 'nullable', 'max:1023']
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized. User must be of type:'
        . strtolower(UserRole::Patient->value) . ' in order to update personal information');
    }
}
