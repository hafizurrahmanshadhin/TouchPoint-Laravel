@extends('backend.app')

@section('title', 'Create choose plan')

@section('content')

    <div class="page-content">
        <div class="container-fluid">
            {{-- start page title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('choose.plan.index') }}">ChoosePlan
                                        Section</a>
                                </li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end page title --}}

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('choose.plan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-2">

                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="title" class="form-label">Title:</label>
                                                <input type="text"
                                                    class="form-control @error('title') is-invalid @enderror" id="title"
                                                    name="title" placeholder="Please Enter Title"
                                                    value="{{ old('title') }}">
                                                @error('title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                            <div class="col-md-6">
                                                <label for="price" class="form-label">Price:</label>
                                                <input type="number"
                                                    class="form-control @error('price') is-invalid @enderror" id="price"
                                                    name="price" placeholder="Please Enter Price"
                                                    value="{{ old('price') }}">
                                                @error('price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        </div>

                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="billing_cycle" class="form-label">Billing Cycle:</label>
                                                    <select class="form-select @error('billing_cycle') is-invalid @enderror" name="billing_cycle" id="billing_cycle">
                                                        <option value="">-- Select Billing Cycle --</option>
                                                        <option value="free" {{ old('billing_cycle') == 'free' ? 'selected' : '' }}>Free</option>
                                                        <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                        <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                                        <option value="lifetime" {{ old('billing_cycle') == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                                    </select>
                                                    @error('billing_cycle')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>   
                                                
                                                <div class="col-md-6">
                                                    <label for="touchpoint_limit" class="form-label">Touchpoint Limit:</label>
                                                    <input type="number"
                                                           class="form-control @error('touchpoint_limit') is-invalid @enderror"
                                                           id="touchpoint_limit"
                                                           name="touchpoint_limit"
                                                           placeholder="Enter Touchpoint Limit"
                                                           value="{{ old('touchpoint_limit') }}">
                                                    @error('touchpoint_limit')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>                                  
                                            {{-- has ads --}}
                                            <div class="col-md-12">
                                                <label for="has_ads" class="form-label">Has Ads:</label>
                                                <select class="form-select @error('has_ads') is-invalid @enderror" name="has_ads" id="has_ads">
                                                    <option value="">-- Select Has Ads --</option>
                                                    <option value="true" {{ old('has_ads') == 'true' ? 'selected' : '' }}>Yes</option>
                                                    <option value="false" {{ old('has_ads') == 'false' ? 'selected' : '' }}>No</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="container">
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{ route('choose.plan.index') }}" class="btn btn-danger">Cancel</a>
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.description').forEach(textarea => {
                ClassicEditor
                    .create(textarea)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endpush
