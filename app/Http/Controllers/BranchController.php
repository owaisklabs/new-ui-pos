<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\UserBranchRole;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:branches',
            'code' => 'required|unique:branches',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch created successfully');
    }

    public function show(Branch $branch)
    {
        $userRoles = $branch->userBranchRoles()->with('user', 'role')->get();
        return view('branches.show', compact('branch', 'userRoles'));
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|unique:branches,name,' . $branch->id,
            'code' => 'required|unique:branches,code,' . $branch->id,
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->sales()->count() > 0 || $branch->purchases()->count() > 0) {
            return redirect()->route('branches.index')->with('error', 'Cannot delete branch with transactions');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully');
    }

    public function assignUser(Request $request, Branch $branch)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        UserBranchRole::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'branch_id' => $branch->id,
            ],
            [
                'role_id' => $request->role_id,
                'is_active' => true,
            ]
        );

        return redirect()->route('branches.show', $branch)->with('success', 'User assigned successfully');
    }

    public function removeUser(Request $request, Branch $branch)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        UserBranchRole::where('user_id', $request->user_id)
            ->where('branch_id', $branch->id)
            ->delete();

        return redirect()->route('branches.show', $branch)->with('success', 'User removed successfully');
    }
}
