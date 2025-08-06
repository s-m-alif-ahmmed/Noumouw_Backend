@extends('backend.app')

@section('title', 'subscription')
@section('title_url')
    <a href="{{ route('subscription.index') }}">Subscription Plan</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .text-center {
            text-align: end;
        }

        .table-topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .dataTables_info {
            margin-top: 20px;
        }

        .form-input {
            border: 2px solid #f0f3f7;
            border-radius: 6px;
        }
    </style>
@endpush


{{-- Main content of the News Dashboard page --}}
@section('content')
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-between mb-6">
                <h1 class="text-xl">Edit Subscription Plan</h1>
                <a href="{{ route('subscription.index') }}"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring
                    active:ring-custom-100 dark:ring-custom-400/20">Back</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('subscription.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex  gap-5 mb-5">
                    <div class="w-full md:w-1/2">
                        <x-backend.input type="text" name="name" label="Subscription Name"
                            value="{{ old('name', $data->name) }}" :required="true" />

                        <label class="block font-medium text-gray-700 mt-4">Select a Plan Type</label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" value="monthly" @if($data->duration === 'monthly') checked @endif name="duration" class="form-radio text-blue-600">
                                <span class="ml-2">Monthly</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" value="yearly" @if($data->duration === 'yearly') checked @endif name="duration" class="form-radio text-blue-600">
                                <span class="ml-2">Yearly</span>
                            </label>
                        </div>
                    </div>

                    <div class="w-full gap-y-5  md:w-1/2">
                        <div>
                            <x-backend.input type="number"  name="price" value="{{ old('price', $data->price) }}" label="Price" :required="true" />
                        </div>
                        <div style="margin-top:20px">
                            <x-backend.input type="text" name="revenue_cart_product_id"  value="{{ old('revenue_cart_product_id', $data->revenue_cart_product_id) }}"
                                 label="Revenue Cart Product ID" :required="true" />
                        </div>
                    </div>
                </div>

                {{-- Form Buttons --}}
                <div class="flex justify-start mt-6 gap-4">
                    <button type="submit"
                        class="btn bg-custom-500 text-white hover:bg-custom-600 focus:ring focus:ring-custom-100">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
