<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $members = Member::with(['savings', 'loans', 'totalRepayments'])->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string',
            'id_number' => 'required|string',
        ]);

        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_number' => $request->id_number,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'membership_date' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('admin.members')->with('success', 'Member added successfully.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
        ]);

        $member->update($request->all());

        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    // Toggle status (active/inactive)
    public function toggleStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->status = $member->status === 'active' ? 'inactive' : 'active';
        $member->save();

        return redirect()->route('admin.members')->with('success', 'Member status updated successfully.');
    }
}
