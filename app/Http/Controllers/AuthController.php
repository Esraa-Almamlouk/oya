<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
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

        $chartStartDate = Carbon::today()->subDays(6);
        $dailyCashflow = Transaction::query()
            ->selectRaw("
                DATE(`date`) as day,
                COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END), 0) as incoming,
                COALESCE(SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END), 0) as outgoing
            ")
            ->whereDate('date', '>=', $chartStartDate)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $projectStatusLabels = [];
        $projectStatusIncoming = [];
        $projectStatusOutgoing = [];
        $arabicDayLabels = [
            0 => 'السبت',
            1 => 'الاحد',
            2 => 'الاثنين',
            3 => 'الثلاثاء',
            4 => 'الاربعاء',
            5 => 'الخميس',
            6 => 'الجمعة',
        ];

        foreach (range(0, 6) as $offset) {
            $day = $chartStartDate->copy()->addDays($offset);
            $row = $dailyCashflow->get($day->toDateString());

            $projectStatusLabels[] = $arabicDayLabels[$day->dayOfWeek] ?? $day->format('D');
            $projectStatusIncoming[] = (float) ($row->incoming ?? 0);
            $projectStatusOutgoing[] = (float) ($row->outgoing ?? 0);
        }

        $recentTransactions = Transaction::query()
            ->with('account:id,name')
            ->latest('date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'usersCount',
            'accountsCount',
            'transactionsCount',
            'todayTransactionsCount',
            'totalIncoming',
            'totalOutgoing',
            'projectStatusLabels',
            'projectStatusIncoming',
            'projectStatusOutgoing',
            'recentTransactions'
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
