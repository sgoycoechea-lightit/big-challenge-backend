<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        $hasCompletedInformation = $user->patient != null;

        return $user->can('create submissions') && $hasCompletedInformation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:1', 'max:255'],
            'symptoms' => ['required', 'min:1', 'max:1023'],
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized. User must be of type:'
        . strtolower(UserRole::Patient->value) . ' and have updated their profile in order to create submissions');
    }
}
