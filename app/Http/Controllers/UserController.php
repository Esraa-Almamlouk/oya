<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest('id')->get();
        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'is_active' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $messages = $this->validationMessages();
        $attributes = $this->validationAttributes();

        $validated = $request->validate($rules, $messages, $attributes);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'تمت إضافة المستخدم بنجاح.');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
        $messages = $this->validationMessages();
        $attributes = $this->validationAttributes();

        $validated = $request->validate($rules, $messages, $attributes);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'message' => 'لا يمكن حذف حسابك الحالي.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'تم حذف المستخدم بنجاح.',
        ]);
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب.',
            'email' => 'صيغة :attribute غير صحيحة.',
            'unique' => ':attribute مستخدم مسبقًا.',
            'max' => 'حقل :attribute يجب ألا يتجاوز :max حرفًا.',
            'min' => 'حقل :attribute يجب ألا يقل عن :min أحرف.',
            'confirmed' => 'تأكيد :attribute غير متطابق.',
            'boolean' => 'قيمة :attribute غير صحيحة.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'is_active' => 'الحالة',
            'password' => 'كلمة المرور',
        ];
    }
}