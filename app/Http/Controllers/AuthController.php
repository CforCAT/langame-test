<?php

namespace App\Http\Controllers;

use App\Exceptions\CodeErrorException;
use App\Exceptions\CodeThrottleException;
use App\Exceptions\UserAlreadyRegisteredException;
use App\Exceptions\UserNotRegisteredException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(Request $request): View
    {
        return view('auth.login');
    }

    public function loginPost(LoginRequest $request, AuthService $authService): JsonResponse
    {
        try {
            $authService->login($request->input('phone'));
        } catch (UserNotRegisteredException) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не зарегистрирован'
            ], 422);
        } catch (CodeThrottleException $e) {
            return response()->json([
                'success' => false,
                'message' => "Попробуйте снова через $e->time секунд"
            ], 400);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function register(Request $request): View
    {
        return view('auth.register');
    }

    public function registerPost(RegisterRequest $request, AuthService $authService): JsonResponse
    {
        try {
            $authService->register($request->input('phone'), $request->input('name'));
        } catch (UserAlreadyRegisteredException) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь уже зарегистрирован'
            ], 422);
        } catch (CodeThrottleException $e) {
            return response()->json([
                'success' => false,
                'message' => "Попробуйте снова через $e->time секунд"
            ], 400);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function verify(VerifyRequest $request, AuthService $authService): JsonResponse
    {
        try {
            $user = $authService->verify($request->input('phone'), $request->input('code'));
        } catch (UserNotRegisteredException) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не зарегистрирован'
            ], 422);
        } catch (CodeErrorException) {
            return response()->json([
                'success' => false,
                'message' => "Неверный код"
            ], 400);
        }

        Auth::login($user, true);

        return response()->json([
            'success' => true,
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
