<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Exceptions\CodeErrorException;
use App\Exceptions\CodeThrottleException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Exceptions\UserNotRegisteredException;
use App\Models\Code;
use App\Models\User;
use App\Notifications\CodeRequest;
use App\Services\Auth\CodeGenerator;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function getUserByPhone(string $phone): ?User
    {
        return User::wherePhone($phone)->first();
    }

    public function register(string $phone, string $name): User
    {
        return DB::transaction(function () use ($phone, $name) {
            if (($user = $this->getUserByPhone($phone)) !== null && $user->registered_at !== null) {
                throw new UserAlreadyRegisteredException();
            }

            if (!$user) {
                $user = User::create([
                    'phone' => $phone,
                    'name' => $name,
                ]);
            } else {
                $user->update([
                    'name' => $name
                ]);
            }
            /* @var User $user */

            $this->sendCode($user);

            return $user;
        }, 3);
    }

    public function login(string $phone): User
    {
        return DB::transaction(function () use ($phone) {
            if (($user = $this->getUserByPhone($phone)) === null || $user->registered_at === null) {
                throw new UserNotRegisteredException();
            }

            $this->sendCode($user);

            return $user;
        }, 3);
    }

    protected function sendCode(User $user): void
    {
        $last = $user->codes()->orderBy('created_at', 'desc')->first();

        if ($last && $last->created_at->greaterThan(now()->subMinutes(config('auth.code.throttle')))) {
            throw new CodeThrottleException(abs($last->created_at->diffInSeconds(now()->subMinutes(config('auth.code.throttle')))));
        }

        $code = app(CodeGenerator::class)->generate(config('auth.code.length'));

        Code::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(config('auth.code.expire'))
        ]);

        $user->notify(new CodeRequest($code));
    }

    public function verify(string $phone, string $code): User
    {
        return DB::transaction(function () use ($phone, $code) {
            if (($user = $this->getUserByPhone($phone)) === null) {
                throw new UserNotRegisteredException();
            }

            if (($code = $user->codes()->whereVerified(false)->where('expires_at', '>=', now())
                    ->whereCode($code)
                    ->first()) === null) {
                throw new CodeErrorException();
            }

            $code->verified = true;
            $code->save();

            if ($user->registered_at === null) {
                $user->registered_at = now();
                $user->save();

                event(new UserRegistered($user));
            }

            return $user;
        });
    }
}
