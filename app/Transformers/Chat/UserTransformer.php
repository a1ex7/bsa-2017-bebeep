<?php

namespace App\Transformers\Chat;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $birthDate = $user->birth_date;

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'permissions' => $user->permissions,
            'birth_date' => $user->birth_date,
            'birth_date_x' => $birthDate ?? $user->birth_date->timestamp,
            'avatar' => $user->getAvatarUrl(),
        ];
    }
}
