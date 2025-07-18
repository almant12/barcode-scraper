@extends('layout.master')

@section('title', 'Scan Barcode')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow max-w-md mx-auto">
        <h2 class="text-lg font-semibold mb-4">Enter or Scan Barcode</h2>

        <form id="barcodeForm" enctype="multipart/form-data">

            <div class="relative">
                <input type="number" name="barcode" placeholder="Enter barcode"
                    class="w-full border rounded-lg pl-4 pr-12 py-2" />
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16m4-16v16m4-16v16m8-16v16m-4-16v16" />
                    </svg>
                </button>
            </div>

            <input type="file" name="barcode_image" id="barcodeFile" accept="image/*" capture="environment"
                class="hidden" />

            <button type="submit" class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
@endsection

@vite('resources/js/barcode.js')
