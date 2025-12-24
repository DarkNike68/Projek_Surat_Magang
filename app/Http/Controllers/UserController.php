<?php

namespace App\Http\Controllers; // Atau App\Http\Controllers\Admin jika Anda memindahkannya

use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('unit')->orderBy('name')->get();
        
        // Mengarahkan ke view di dalam folder admin
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $units = Unit::orderBy('name')->get();
        
        // Mengarahkan ke view di dalam folder admin
        return view('admin.users.edit', compact('user', 'units'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'unit_id' => 'nullable|exists:units,id'
        ]);

        $user->update([
            'unit_id' => $request->unit_id
        ]);

        // PERBAIKAN: Menggunakan nama rute yang benar dengan prefix 'admin.'
        return redirect()->route('admin.users.index')->with('success', 'Unit untuk user berhasil diperbarui!');
    }
}