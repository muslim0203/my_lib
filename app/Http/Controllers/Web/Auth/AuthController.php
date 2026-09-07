<?php

namespace App\Http\Controllers\Web\Auth;

use App\Core\Enums\Auth\LoginTypeEnum;
use App\Core\Services\Auth\AuthenticationService;
use App\Http\Requests\Auth\LoginByUsernameAndPassRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationService $authenticationService
    )
    {
    }

    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function view(): Application|Factory|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.login');
    }


    /**
     * @param LoginByUsernameAndPassRequest $loginByUsernameAndPassRequest
     * @return RedirectResponse
     */
    public function login(LoginByUsernameAndPassRequest $loginByUsernameAndPassRequest): RedirectResponse
    {
        $response = $this->authenticationService->login($loginByUsernameAndPassRequest, LoginTypeEnum::_LOGIN_LOGIN_PASS->value);

        if ($response['success']) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors($response['errors'])->onlyInput('username');
    }

    /**
     * @return RedirectResponse|void
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();

            return redirect()->route('login');
        }

        abort(400, __('client.User is not authentication'));
    }
}
