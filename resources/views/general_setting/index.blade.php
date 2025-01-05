@extends('layouts.app')

@section('content')
<form action="{{ route('settings.createOrUpdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-5">
        <div class="col-xl-9">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="personal-info" role="tabpanel">
                            <div class="p-sm-3 p-0">

                                <h6 class="fw-medium mb-3">Website Name :</h6>
                                <div class="mb-4">
                                    <label for="website_name" class="form-label">Website Name</label>
                                    <input type="text" name="website_name" class="form-control" value="{{ old('website_name', $setting->website_name ?? '') }}">
                                </div>

                                <h6 class="fw-medium mb-3">Logo :</h6>
                                <div class="mb-4">
                                    <label for="logo-upload" class="form-label">Upload Logo</label>
                                    <input type="file" name="logo" class="form-control" id="logo-upload" onchange="previewLogo(event)">
                                    <div class="mt-3">
                                        <img src="{{ asset($setting->logo ?? 'default-logo.png') }}" alt="Logo Preview" id="logo-preview" class="img-fluid img-rectangular">
                                    </div>
                                </div>

                                <h6 class="fw-medium mb-3">Favicon :</h6>
                                <div class="mb-4">
                                    <label for="favicon-upload" class="form-label">Upload Favicon</label>
                                    <input type="file" name="favicon" class="form-control" id="favicon-upload" onchange="previewFavicon(event)">
                                    <div class="mt-3">
                                        <img src="{{ asset($setting->favicon ?? 'default-favicon.png') }}" alt="Favicon Preview" id="favicon-preview" class="img-fluid img-rectangular">
                                    </div>
                                </div>

                                <h6 class="fw-medium mb-3">Rapid API Key :</h6>
                                <div class="row gy-4 mb-4">
                                    <div class="col-xl-12">
                                        <label for="rapid_api_key" class="form-label">Rapid API Key</label>
                                        <input type="text" name="rapid_api_key" class="form-control" value="{{ old('rapid_api_key', $setting->rapid_api_key ?? '') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="float-end">
                        <button type="reset" class="btn btn-light m-1">Restore Defaults</button>
                        <button type="submit" class="btn btn-primary m-1">Save Changes</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

<script>
    function previewLogo(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function previewFavicon(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('favicon-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
