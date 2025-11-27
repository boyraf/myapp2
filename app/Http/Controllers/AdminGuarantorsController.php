<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guarantor;
use App\Models\Loan;

class AdminGuarantorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index($loanId)
    {
        $loan = Loan::with('member')->findOrFail($loanId);
        $guarantors = Guarantor::with('member')->where('loan_id', $loan->id)->get();
        return view('admin.guarantors.index', compact('loan','guarantors'));
    }

    public function destroy($loanId, $id)
    {
        $guarantor = Guarantor::findOrFail($id);
        $guarantor->delete();
        return back()->with('success', 'Guarantor removed');
    }
}
