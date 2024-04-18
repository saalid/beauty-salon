<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function getName($phoneNumber)
    {
        return User::where('phone', $phoneNumber)->firstOrFail()->name;
    }
}
