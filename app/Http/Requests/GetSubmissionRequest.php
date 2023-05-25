<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class GetSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        $isCompletePatient = $user->hasRole(UserRole::Patient->value) && $user->patient != null;
        $belongsToPatient = $this->submission->patient_id === $user->patient?->id;

        return $isCompletePatient && $belongsToPatient;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized. User must be of type:'
        . strtolower(UserRole::Patient->value) . ' and owner of this submission');
    }
}
