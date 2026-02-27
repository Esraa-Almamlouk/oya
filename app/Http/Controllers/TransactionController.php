<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function store(Request $request, Account $account)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', Rule::in(['credit', 'debit'])],
            'value' => ['required', 'numeric', 'min:0'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        $amount = round((float) $validated['value'] * (float) $validated['exchange_rate'], 2);

        DB::transaction(function () use ($account, $validated, $amount, $request) {
            $lockedAccount = Account::query()->lockForUpdate()->findOrFail($account->id);

            $balanceBefore = (float) $lockedAccount->balance;
            $balanceAfter = $validated['type'] === 'credit'
                ? $balanceBefore + $amount
                : $balanceBefore - $amount;

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('transactions', 'public');
            }

            $transaction = Transaction::create([
                'account_id' => $lockedAccount->id,
                'date' => $validated['date'],
                'type' => $validated['type'],
                'amount' => $amount,
                'balance_before' => round($balanceBefore, 2),
                'balance_after' => round($balanceAfter, 2),
                'description' => $validated['description'],
                'attachment' => $attachmentPath,
            ]);

            $referenceDate = Carbon::parse($validated['date'])->format('ymd');
            $transaction->update([
                'reference' => sprintf('OYA-%s-%04d', $referenceDate, $transaction->id),
            ]);

            $lockedAccount->update([
                'balance' => round($balanceAfter, 2),
            ]);
        });

        return redirect()
            ->route('accounts.show', $account)
            ->with('success', 'تمت إضافة المعاملة وتحديث رصيد الحساب بنجاح.');
    }
}
