<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     *
     * @return array
     */
    public function transform(User $user)
    {
        $role = $this->getUserRole($user);
        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'role' => (string) $role,
            'phone_number' => $user->patient ? ((string) $user->patient->phone_number) : null,
            'weight' => $user->patient ? ((string) $user->patient->weight) : null,
            'height' => $user->patient ? ((string) $user->patient->height) : null,
            'other_information' => $user->patient ? ((string) $user->patient->other_information) : null,
        ];
    }

    /**
     * Get the user role.
     *
     *
     */
    protected function getUserRole(User $user): ?string
    {
        /** @var \Spatie\Permission\Models\Role|null $role */
        $role = $user->roles->first();
        return $role?->name;
    }
}
