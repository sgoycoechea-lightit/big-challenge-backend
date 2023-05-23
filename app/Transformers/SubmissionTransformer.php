<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Submission;
use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class SubmissionTransformer extends Transformer
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
    public function transform(Submission $submission)
    {
        return [
            'id' => (int) $submission->id,
            'title' => (string) $submission->title,
            'symptoms' => (string) $submission->symptoms,
            'status' => (string) $submission->status->value,
            'doctor' => $this->transformUser($submission->doctor),
            'patient' => $this->transformUser($submission->patient?->user),
        ];
    }

    protected function transformUser(User|null $user): array|null
    {
        if (! $user) {
            return null;
        }
        $userTransformer = new UserTransformer();
        return $userTransformer->transform($user);
    }
}
