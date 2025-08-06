@extends('backend.app')

@section('title', 'subscription')
@section('title_url')
    <a href="{{ route('subscription.index') }}">Subscription</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <style>
        .table-topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
    </style>
@endpush

{{-- Main content of the News Dashboard page --}}
@section('content')
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-between mb-6">
                <h1 class="text-xl font-semibold">Create a Subscription</h1>
                <a href="{{ route('subscription.index') }}"
                   class="btn bg-custom-500 text-white hover:bg-custom-600 focus:ring focus:ring-custom-100">
                    Back
                </a>
            </div>


            {{-- subscription form --}}
            <form action="{{ route('subscription.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex  gap-5 mb-5">
                    <div class="w-full md:w-1/2">
                        <x-backend.input type="text" name="name" label="Subscription Name" :required="true" />

                        <label class="block font-medium text-gray-700 mt-4">Select a Plan Type</label>
                        <div class="mt-2  space-y-2 space-x-3">
                            <label class="inline-flex items-center ">
                                <input type="radio" value="monthly" name="duration" class="form-radio text-blue-600">
                                <span class="ml-1">Monthly</span>
                            </label>
                            <label class="inline-flex  items-center">
                                <input type="radio" value="yearly" name="duration" class="form-radio text-blue-600" checked>
                                <span class="ml-1">Yearly</span>
                            </label>
                        </div>
                    </div>

                    <div class="w-full   md:w-1/2">
                        <div>
                            <x-backend.input type="number" name="price" label="Price" :required="true" />
                        </div>
                         <div  style="margin-top:20px">
                            <x-backend.input type="text" name="revenue_cart_product_id"  label="Revenue Cart Product ID" :required="true" />
                         </div>

                    </div>
                </div>

                {{-- Form Buttons --}}
                <div class="flex justify-start mt-6 gap-4">
                    <button type="submit"
                            class="btn bg-custom-500 text-white hover:bg-custom-600 focus:ring focus:ring-custom-100">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
