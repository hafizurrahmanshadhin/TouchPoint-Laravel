@extends('backend.app')

@section('title', 'Edit choose plan')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        {{-- start page title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('choose.plan.index')}}">ChoosePlan
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
                        <form action="{{route('choose.plan.update', $choosePlan->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-2">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="title" class="form-label">Title:</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" placeholder="Please Enter Title"
                                                value="{{ old('title',$choosePlan->title) }}">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price:</label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                id="price" name="price" placeholder="Please Enter Price"
                                                value="{{ old('price',$choosePlan->price) }}">
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                    </div>
                                </div>
                                </div>

                                <div class="container">
                                    <div class="row mt-3">
                                         {{-- Billing Cycle --}}
                                        <div class="col-md-6">
                                           <label for="billing">Billing Cycle</label>
                                            <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                                id="billing_cycle" name="billing_cycle">
                                                <option value="">Select Billing Cycle</option>
                                                <option value="free" {{ old('billing_cycle',$choosePlan->billing_cycle) == 'free' ? 'selected' : '' }}>free</option>
                                                <option value="monthly" {{ old('billing_cycle',$choosePlan->billing_cycle) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="yearly" {{ old('billing_cycle',$choosePlan->billing_cycle) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                                <option value="lifetime" {{ old('billing_cycle',$choosePlan->billing_cycle) == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                            </select>
                                            @error('billing_cycle')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Touchpoint Limit --}}

                                        <div class="col-md-6">
                                            <label for="touchpoint_limit">Touchpoint Limit</label>
                                            <input type="number" class="form-control @error('touchpoint_limit') is-invalid @enderror"
                                                id="touchpoint_limit" name="touchpoint_limit" placeholder="Please Enter Touchpoint Limit"
                                                value="{{ old('touchpoint_limit',$choosePlan->touchpoint_limit) }}">
                                            @error('touchpoint_limit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>



                                <div class="container">
                                    <div class="row mt-3">
                                     {{-- has add edit --}}
                                        <div class="col-md-12">

                                            <label for="has_ads">Has Ads</label>
                                            <select class="form-select @error('has_ads') is-invalid @enderror"
                                                id="has_ads" name="has_ads">
                                                <option value="">Select Has Ads</option>
                                                <option value="1" {{ old('has_ads',$choosePlan->has_ads) == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('has_ads',$choosePlan->has_ads) == '0' ? 'selected' : '' }}>No</option>
                                            </select>

                                        </div>
                                </div>


                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{route('choose.plan.index')}}" class="btn btn-danger">Cancel</a>
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