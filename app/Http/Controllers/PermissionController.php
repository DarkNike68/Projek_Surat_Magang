<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use App\Models\UnitPermission;
use App\Models\User;
use App\Models\UserPermissionException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $existingPermissions = UnitPermission::all();
        
        $permissions = [];
        foreach ($existingPermissions as $p) {
            $permissions[$p->category_id . '-' . $p->unit_id] = $p;
        }

        return view('permissions.index', compact('units', 'categories', 'permissions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
            'permission_type' => 'required|in:read,write',
            'is_checked' => 'required|boolean',
        ]);

        UnitPermission::updateOrCreate(
            [
                'unit_id' => $request->unit_id,
                'category_id' => $request->category_id,
            ],
            [
                'can_' . $request->permission_type => $request->is_checked,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function searchUsers(Request $request)
    {
        $request->validate(['unit_id' => 'required|exists:units,id']);
        $searchTerm = $request->query('search', '');
        $categoryId = $request->query('category_id');

        $usersQuery = User::where('unit_id', $request->unit_id);

        if (!empty($searchTerm)) {
            $usersQuery->where('name', 'LIKE', "%{$searchTerm}%");
        }

        $users = $usersQuery->orderBy('name')
            ->with(['permissionExceptions' => function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            }])
            ->get();

        return response()->json($users);
    }
    
    public function getExceptions(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $usersWithExceptions = User::where('unit_id', $request->unit_id)
            ->whereHas('permissionExceptions', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->with(['permissionExceptions' => function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            }])
            ->get();

        return response()->json($usersWithExceptions);
    }

    public function updateException(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'permissions' => 'required|array'
        ]);

        foreach ($request->permissions as $p) {
            UserPermissionException::updateOrCreate(
                [
                    'user_id' => $p['user_id'],
                    'category_id' => $request->category_id,
                ],
                [
                    'can_read' => $p['can_read'],
                    'can_write' => $p['can_write'],
                ]
            );
        }
        return response()->json(['success' => true, 'message' => 'Pengecualian berhasil disimpan!']);
    }
}