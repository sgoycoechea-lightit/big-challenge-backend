<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\UserRole;
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
        $data = [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'role' => (string) $role,
        ];

        if ($role === UserRole::PATIENT->value) {
            if ($user->phone_number) {
                $data['phone_number'] = (string) $user->phone_number;
            }
            if ($user->height) {
                $data['height'] = (float) $user->height;
            }
            if ($user->weight) {
                $data['weight'] = (float) $user->weight;
            }
            if ($user->other_information) {
                $data['other_information'] = (string) $user->other_information;
            }
        }

        return $data;
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
