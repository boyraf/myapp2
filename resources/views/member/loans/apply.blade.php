@extends('layouts.member')

@section('content')
<h2>Apply for a Loan</h2>

<p>Your current savings: {{ number_format(auth('member')->user()->totalSavings(), 2) }}</p>
<p>Maximum loan you can apply for: {{ number_format(auth('member')->user()->totalSavings() * 3, 2) }}</p>

{{-- Success message --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Error messages --}}
@if($errors->any())
    <div class="alert alert-danger">
        <strong>There were some problems with your input:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('member.loans.storeApply') }}" method="POST">
    @csrf

    {{-- Loan details --}}
    <div class="form-group mb-3">
        <label for="amount">Loan Amount</label>
        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required min="100">
        @error('amount')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="repayment_period">Repayment Period (months)</label>
        <input type="number" name="repayment_period" class="form-control" value="{{ old('repayment_period') }}" required min="1" max="60">
        @error('repayment_period')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Guarantors section --}}
    <h4>Guarantors</h4>
    <div id="guarantors" class="mb-3">
        <div class="guarantor-entry mb-2">
            <div class="form-group mb-2">
                <label for="guarantors[0][name]">Guarantor Name</label>
                <input type="text" name="guarantors[0][name]" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label for="guarantors[0][amount_guaranteed]">Amount Guaranteed</label>
                <input type="number" name="guarantors[0][amount_guaranteed]" class="form-control" required min="1">
            </div>
        </div>
    </div>

    {{-- Buttons aligned with inputs --}}
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-secondary" onclick="addGuarantor()">+ Add Guarantor</button>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </div>
</form>

<script>
function addGuarantor() {
    const index = document.querySelectorAll('#guarantors .guarantor-entry').length;
    const container = document.getElementById('guarantors');
    container.insertAdjacentHTML('beforeend', `
        <div class="guarantor-entry mb-2">
            <div class="form-group mb-2">
                <label for="guarantors[${index}][name]">Guarantor Name</label>
                <input type="text" name="guarantors[${index}][name]" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label for="guarantors[${index}][amount_guaranteed]">Amount Guaranteed</label>
                <input type="number" name="guarantors[${index}][amount_guaranteed]" class="form-control" required min="1">
            </div>
        </div>
    `);
}
</script>
@endsection
