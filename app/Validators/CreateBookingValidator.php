<?php

namespace App\Validators;

use App\User;
use App\Models\Trip;
use App\Rules\Booking\CreateBookingRule;
use App\Services\Requests\CreateBookingRequest;

class CreateBookingValidator
{
    protected $rules;

    public function __construct(CreateBookingRule ...$rules)
    {
        $this->rules = $rules;
    }

    public function validate(Trip $trip, User $user, CreateBookingRequest $request): bool
    {
        foreach ($this->rules as $rule) {
            $rule->validate($trip, $user, $request);
        }

        return true;
    }
}
