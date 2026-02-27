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
            'phone' => ['required', 'digits:10', 'starts_with:091,092,093,094', 'unique:users,phone'],
            'is_active' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $messages = $this->validationMessages();
        $attributes = $this->validationAttributes();

        $validated = $request->validate($rules, $messages, $attributes);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'طھظ…طھ ط¥ط¶ط§ظپط© ط§ظ„ظ…ط³طھط®ط¯ظ… ط¨ظ†ط¬ط§ط­.');
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
                'digits:10',
                'starts_with:091,092,093,094',
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
            ->with('success', 'طھظ… طھط­ط¯ظٹط« ط¨ظٹط§ظ†ط§طھ ط§ظ„ظ…ط³طھط®ط¯ظ… ط¨ظ†ط¬ط§ط­.');
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'message' => 'ظ„ط§ ظٹظ…ظƒظ† ط­ط°ظپ ط­ط³ط§ط¨ظƒ ط§ظ„ط­ط§ظ„ظٹ.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'طھظ… ط­ط°ظپ ط§ظ„ظ…ط³طھط®ط¯ظ… ط¨ظ†ط¬ط§ط­.',
        ]);
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'ط­ظ‚ظ„ :attribute ظ…ط·ظ„ظˆط¨.',
            'email' => 'طµظٹط؛ط© :attribute ط؛ظٹط± طµط­ظٹط­ط©.',
            'unique' => ':attribute ظ…ط³طھط®ط¯ظ… ظ…ط³ط¨ظ‚ظ‹ط§.',
            'max' => 'ط­ظ‚ظ„ :attribute ظٹط¬ط¨ ط£ظ„ط§ ظٹطھط¬ط§ظˆط² :max ط­ط±ظپظ‹ط§.',
            'min' => 'ط­ظ‚ظ„ :attribute ظٹط¬ط¨ ط£ظ„ط§ ظٹظ‚ظ„ ط¹ظ† :min ط£ط­ط±ظپ.',
            'confirmed' => 'طھط£ظƒظٹط¯ :attribute ط؛ظٹط± ظ…طھط·ط§ط¨ظ‚.',
            
            'phone.digits' => 'رقم الهاتف يجب أن يكون 10 أرقام.',
            'phone.starts_with' => 'رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 094.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'name' => 'ط§ظ„ط§ط³ظ…',
            'email' => 'ط§ظ„ط¨ط±ظٹط¯ ط§ظ„ط¥ظ„ظƒطھط±ظˆظ†ظٹ',
            'phone' => 'ط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپ',
            'is_active' => 'ط§ظ„ط­ط§ظ„ط©',
            'password' => 'ظƒظ„ظ…ط© ط§ظ„ظ…ط±ظˆط±',
        ];
    }
}
