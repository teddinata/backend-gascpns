@extends('layouts.master')
@section('title', 'Transaction Details')

@section('content')

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    {{ $errors->first() }}
</div>
@endif

@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    {{ session('success') }}
</div>
@endif

<div class="w-[1280px] mx-auto my-10 p-8 bg-white shadow-lg rounded-lg">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-2">Invoice #{{ $transaction->invoice_code }}</h2>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <h5 class="font-semibold">Student Information</h5>
            <p>Name: {{ $transaction->student->name }}</p>
            <p>Student ID: {{ $transaction->student_id }}</p>
            <p>Email: {{ $transaction->student->email }}</p>
            <p>Phone: {{ $transaction->student->phone }}</p>
            <p>Age: {{ $transaction->student->age }}</p>
        </div>
        <div class="text-right">
            <h5 class="font-semibold">Transaction Details</h5>
            <p>Date: {{ $transaction->created_at ? Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y, H:i') : '-' }}</p>
            <p>Payment Date: {{ $transaction->payment_date ? Carbon\Carbon::parse($transaction->payment_date)->format('d/m/Y, H:i') : '-' }}</p>
            <p>Status: {{ ucfirst($transaction->payment_status) }}</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Package Name</th>
                    <th class="py-3 px-6 text-center">Quantity</th>
                    <th class="py-3 px-6 text-center">Price</th>
                    <th class="py-3 px-6 text-center">Original Price</th>
                    <th class="py-3 px-6 text-center">Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($transaction->details as $detail)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $detail->package_name }}</td>
                    <td class="py-3 px-6 text-center">{{ $detail->quantity }}</td>
                    <td class="py-3 px-6 text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-center">Rp {{ number_format($detail->original_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-center">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-6">
        <div>
            <h5 class="font-semibold">Payment Method: {{ $transaction->payment_method }}</h5>
            <h5 class="font-semibold mt-4">Student Purchased By:</h5>
            @if ($transaction->studentTransaction)
            <p>Name: {{ $transaction->studentTransaction->name }}</p>
            <p>Email: {{ $transaction->studentTransaction->email }}</p>
            <p>Phone: {{ $transaction->studentTransaction->phone }}</p>
            @else
            <p>Student purchased by themselves</p>
            @endif
        </div>
        <div class="text-right">
            <h5 class="font-semibold">Total Amount: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="mt-6 text-right">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Print Invoice</button>
    </div>
</div>

@endsection
