@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">

    <a href="{{ route('properties.List') }}" class="btn btn-outline-secondary mb-3">
        ← Go Back
    </a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Properties Settings</h5>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title
                            ">Hot Properties Images</h5>
                            <p class="card-text">Here you can manage the images of Hot Properties Section.</p>

                            <a href="{{ route('properties.editHotPropertiesImages') }}" class="btn btn-warning">Edit Images</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title
                            ">Properties Features</h5>
                            <p class="card-text">Here you can manage the content of Properties Features Section.</p>

                            <a href="{{ route('properties.editPropertiesFeatures') }}" class="btn btn-warning">Edit Features</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection