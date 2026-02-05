<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{   
    /**
     * Display a listing of the users.
     */
    public function index(): Response
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $verified = (bool) ($data['verified'] ?? false);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => $verified ? now() : null,
        ]);

        if (! $verified) {
            event(new Registered($user));
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): Response
    {
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $emailChanged = $data['email'] !== $user->email;
        $verified = (bool) ($data['verified'] ?? false);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if ($emailChanged) {
            $updateData['email_verified_at'] = $verified ? now() : null;
        } else {
            if (array_key_exists('verified', $data)) {
                $updateData['email_verified_at'] = $verified ? now() : null;
            }
        }

        $user->fill($updateData);
        $user->save();

        return redirect()
            ->route('users.edit', $user)
            ->with('success', 'User updated.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $auth = auth()->user();
        if (! $auth) {
            abort(403);
        }

        // simple safeguard: prevent users deleting themselves
        if ($auth->id === $user->id) {
            abort(403, 'You cannot delete your own account.');
        }

        $user->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'User deleted']);
        }

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
