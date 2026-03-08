<?php

namespace App\Http\Controllers;

use App\Enums\Category;
use App\Enums\Currency;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::latest('id')->get();
        $categories = Category::cases();
        $currencies = Currency::cases();

        return view('accounts.index', compact('accounts', 'categories', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(Category::class)],
            'currency' => ['required', Rule::enum(Currency::class)],
            'bank' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:10', 'starts_with:091,092,093,094', 'unique:accounts,phone'],
            'balance' => ['nullable', 'numeric'],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['balance'] = $validated['balance'] ?? 0;

        Account::create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'تمت إضافة الحساب بنجاح.');
    }

    public function show(Account $account)
    {
        $summary = $account->transactions()
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END), 0) as total_incoming,
                COALESCE(SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END), 0) as total_outgoing
            ")
            ->first();

        $totalIncoming = (float) ($summary->total_incoming ?? 0);
        $totalOutgoing = (float) ($summary->total_outgoing ?? 0);
        $netBalance = $totalIncoming - $totalOutgoing;

        $transactions = $account->transactions()
            ->latest('date')
            ->latest('id')
            ->get();

        return view('accounts.show', compact('account', 'transactions', 'totalIncoming', 'totalOutgoing', 'netBalance'));
    }

    public function statementPdf(Account $account)
    {
        @set_time_limit(180);
        @ini_set('max_execution_time', '180');

        $transactions = $account->transactions()
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $totalIncoming = (float) $transactions
            ->where('type', 'credit')
            ->sum('amount');

        $totalOutgoing = (float) $transactions
            ->where('type', 'debit')
            ->sum('amount');

        $currentBalance = (float) ($transactions->last()->balance_after ?? $account->balance ?? 0);

        $html = view('accounts.statement-pdf', [
            'account' => $account,
            'transactions' => $transactions,
            'totalIncoming' => $totalIncoming,
            'totalOutgoing' => $totalOutgoing,
            'currentBalance' => $currentBalance,
            'generatedAt' => now(),
        ])->render();

        $config = (new ConfigVariables())->getDefaults();
        $fontConfig = (new FontVariables())->getDefaults();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => storage_path('app/mpdf'),
            'fontDir' => array_merge($config['fontDir'], [public_path('assets/fonts')]),
            'fontdata' => $fontConfig['fontdata'] + [
                'tajawal' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'B' => 'Tajawal-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 0,
                ],
            ],
            'default_font' => 'tajawal',
            'autoScriptToLang' => false,
            'autoLangToFont' => false,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        $filename = sprintf('account-statement-%d-%s.pdf', $account->id, now()->format('Ymd_His'));

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(Category::class)],
            'currency' => ['required', Rule::enum(Currency::class)],
            'bank' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'digits:10',
                'starts_with:091,092,093,094',
                Rule::unique('accounts', 'phone')->ignore($account->id),
            ],
            'balance' => ['nullable', 'numeric'],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['balance'] = $validated['balance'] ?? 0;

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'تم تحديث بيانات الحساب بنجاح.');
    }

    public function destroy(Account $account): JsonResponse
    {
        $account->delete();

        return response()->json([
            'message' => 'تم حذف الحساب بنجاح.',
        ]);
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب.',
            'unique' => ':attribute مستخدم مسبقًا.',
            'max' => 'حقل :attribute يجب ألا يتجاوز :max حرفًا.',
            'string' => 'قيمة :attribute غير صحيحة.',
            'enum' => 'قيمة :attribute غير صحيحة.',
            'numeric' => 'قيمة :attribute يجب أن تكون رقمًا.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون 10 أرقام.',
            'phone.starts_with' => 'رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 094.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'name' => 'اسم الحساب',
            'category' => 'التصنيف',
            'currency' => 'العملة',
            'bank' => 'المصرف',
            'phone' => 'رقم الهاتف',
            'balance' => 'الرصيد الافتتاحي',
        ];
    }
}

