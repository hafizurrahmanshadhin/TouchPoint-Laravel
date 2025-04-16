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


                        <form action="{{ route('choose.plan.update', $choosePlan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-2">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="plan" class="form-label">Plan:</label>
                                            <select class="form-control @error('plan') is-invalid @enderror" id="plan" name="plan">
                                                <option value="">-- Select Plan --</option>
                                                
                                                <option value="free" 
                                                    {{ old('plan', $choosePlan->plan) == 'free' ? 'selected' : '' }}
                                                    {{ in_array('free', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Free
                                                </option>
                                            
                                                <option value="monthly" 
                                                    {{ old('plan', $choosePlan->plan) == 'monthly' ? 'selected' : '' }}
                                                    {{ in_array('monthly', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Monthly
                                                </option>
                                            
                                                <option value="yearly" 
                                                    {{ old('plan', $choosePlan->plan) == 'yearly' ? 'selected' : '' }}
                                                    {{ in_array('yearly', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Yearly
                                                </option>
                                            
                                                <option value="lifetime" 
                                                    {{ old('plan', $choosePlan->plan) == 'lifetime' ? 'selected' : '' }}
                                                    {{ in_array('lifetime', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Lifetime
                                                </option>
                                            </select>
                                            
                                            @error('plan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                        
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price:</label>
                                            <input type="number" 
                                                class="form-control @error('price') is-invalid @enderror"
                                                id="price" 
                                                name="price"
                                                placeholder="Please Enter Price"
                                                value="{{ old('price', $choosePlan->price) }}">
                                            {{-- <input type="hidden" name="price" id="hidden_price" value="{{ old('price', $choosePlan->price) }}"> --}}
                                            
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
                                            <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                                name="billing_cycle" id="billing_cycle">
                                                <option value="">-- Select Billing Cycle --</option>
                                                <option value="monthly" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'monthly' ? 'selected' : '' }}>
                                                    Monthly
                                                </option>
                                                <option value="yearly" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'yearly' ? 'selected' : '' }}>
                                                    Yearly
                                                </option>
                                                <option value="lifetime" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'lifetime' ? 'selected' : '' }}>
                                                    Lifetime
                                                </option>
                                            </select>
                                            @error('billing_cycle')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="currency" class="form-label">Touchpoint Limit:</label>
                                                          
                                            <select id="touchpoint_type" class="form-select mb-2" name="touchpoint_type" onchange="toggleTouchpointLimit()">
                                                <option value="unlimited" {{ $choosePlan->touchpoint_limit === 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                                <option value="custom" {{ is_numeric($choosePlan->touchpoint_limit) ? 'selected' : '' }}>Custom</option>
                                            </select>
                                            
                                            <input type="number" 
                                                   class="form-control @error('touchpoint_limit') is-invalid @enderror"
                                                   id="touchpoint_limit_input" 
                                                   name="touchpoint_limit"
                                                   placeholder="Enter touchpoint limit"
                                                   value="{{ is_numeric($choosePlan->touchpoint_limit) ? $choosePlan->touchpoint_limit : '' }}"
                                                   {{ $choosePlan->touchpoint_limit === 'unlimited' ? 'style=display:none' : '' }}>
                                            @error('touchpoint_limit_input')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                    </div>
                                </div>
                        
                                {{-- has ads --}}
                                <div class="col-md-12">
                                    <label for="has_ads" class="form-label">Has Ads:</label>
                                    <select class="form-select @error('has_ads') is-invalid @enderror" name="has_ads" id="has_ads">
                                        <option value="">-- Select Has Ads --</option>
                                        <option value="1" {{ old('has_ads', $choosePlan->has_ads) == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('has_ads', $choosePlan->has_ads) == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="container">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
    // Toggle the visibility of the custom input based on the selected value
    function toggleTouchpointLimit() {
        const touchpointTypeSelect = document.getElementById('touchpoint_type');
        const touchpointInput = document.getElementById('touchpoint_limit_input');
        const hiddenTouchpointInput = document.getElementById('touchpoint_limit_hidden');

        if (touchpointTypeSelect.value === 'unlimited') {
            touchpointInput.style.display = 'none';  
            hiddenTouchpointInput.value = 'unlimited';  
        } else {
            touchpointInput.style.display = 'block';
            hiddenTouchpointInput.value = touchpointInput.value;  
        }
    }

    // Call the function to initialize the field state when the page loads
    window.onload = toggleTouchpointLimit;
</script>



@endpush