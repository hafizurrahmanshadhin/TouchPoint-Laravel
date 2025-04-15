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
                        <form action="{{route('choose.plan.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
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


                                <div class="container">
                                    <div class="row mt-3">
                                        <!-- First Column: Description -->
                                        <div class="col-md-6">
                                            <label for="description" class="form-label">Description:</label>
                                            <textarea class="form-control description @error('description') is-invalid @enderror"
                                                id="description" name="description" placeholder="Please Enter Description">{{ old('description',$choosePlan->description) }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- button link --}}

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