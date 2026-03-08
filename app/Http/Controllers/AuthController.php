<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
            'error' => 'البريد الالكتروني او كلمة المرور غير صحيحة !',
            ]);
    }

    public function dashboard(): View
    {
        $usersCount = User::query()->count();
        $accountsCount = Account::query()->count();
        $transactionsCount = Transaction::query()->count();
        $todayTransactionsCount = Transaction::query()
            ->whereDate('created_at', today())
            ->count();

        $totals = Transaction::query()
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END), 0) as total_incoming,
                COALESCE(SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END), 0) as total_outgoing
            ")
            ->first();

        $totalIncoming = (float) ($totals->total_incoming ?? 0);
        $totalOutgoing = (float) ($totals->total_outgoing ?? 0);

        return view('dashboard', compact(
            'usersCount',
            'accountsCount',
            'transactionsCount',
            'todayTransactionsCount',
            'totalIncoming',
            'totalOutgoing'
        ));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
