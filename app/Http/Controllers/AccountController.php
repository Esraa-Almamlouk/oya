<?php

namespace App\Http\Controllers;

use App\Enums\Category;
use App\Enums\Currency;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(Category::class)],
            'currency' => ['required', Rule::enum(Currency::class)],
            'bank' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:accounts,phone'],
        ];
        $messages = $this->validationMessages();
        $attributes = $this->validationAttributes();

        $validated = $request->validate($rules, $messages, $attributes);

        Account::create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'تمت إضافة الحساب بنجاح.');
    }

    public function update(Request $request, Account $account)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(Category::class)],
            'currency' => ['required', Rule::enum(Currency::class)],
            'bank' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('accounts', 'phone')->ignore($account->id),
            ],
        ];
        $messages = $this->validationMessages();
        $attributes = $this->validationAttributes();

        $validated = $request->validate($rules, $messages, $attributes);

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
        ];
    }
}
