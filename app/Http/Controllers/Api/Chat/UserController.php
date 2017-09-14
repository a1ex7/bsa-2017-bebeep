<?php

namespace App\Http\Controllers\Api\Chat;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Chat\UserTransformer;
use App\Services\Contracts\Chat\UserService as ChatUserService;

class UserController extends Controller
{
    /**
     * @var ChatUserService
     */
    private $userService;

    public function __construct(ChatUserService $userService)
    {
        $this->userService = $userService;
    }

    public function others()
    {
        $users = $this->userService->getOthers(Auth::user());

        return fractal()->collection($users, new UserTransformer())->respond();
    }

    public function user(User $user)
    {
        return fractal()->item($user, new UserTransformer())->respond();
    }
}
