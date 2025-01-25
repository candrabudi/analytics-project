@extends('layouts.app')
@section('title', 'EDIT AKUN ' . strtoupper($rawTiktokAccount->nickname))
@section('content')

    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="tab-content">
                <div class="row mb-3">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title" id="kol-title">EDIT DATABASE RAW AKUN TIKTOK
                                    {{ $rawTiktokAccount->nickname }}</div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('kol.type_influencer.update', $rawTiktokAccount->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row mb-3">
                                        <div class="form-group col-md-4">
                                            <label for="author_id">Author ID</label>
                                            <input type="text" class="form-control" id="author_id" name="author_id"
                                                value="{{ $rawTiktokAccount->author_id }}" placeholder="Author ID" readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="unique_id">Unique ID</label>
                                            <input type="text" class="form-control" id="unique_id" name="unique_id"
                                                value="{{ $rawTiktokAccount->unique_id }}" placeholder="Unique ID" readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="nickname">Nickname</label>
                                            <input type="text" class="form-control" id="nickname" name="nickname"
                                                value="{{ $rawTiktokAccount->nickname }}" placeholder="Nickname" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="form-group col-md-4">
                                            <label for="follower">Followers</label>
                                            <input type="number" class="form-control" id="follower" name="follower"
                                                value="{{ $rawTiktokAccount->follower }}" placeholder="Followers" readonly>
                                        </div>

                                        <!-- Following Count -->
                                        <div class="form-group col-md-4">
                                            <label for="following">Following</label>
                                            <input type="number" class="form-control" id="following" name="following"
                                                value="{{ $rawTiktokAccount->following }}" placeholder="Following" readonly>
                                        </div>

                                        <!-- Like Count -->
                                        <div class="form-group col-md-4">
                                            <label for="like">Likes</label>
                                            <input type="number" class="form-control" id="like" name="like"
                                                value="{{ $rawTiktokAccount->like }}" placeholder="Likes" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <!-- Total Videos -->
                                        <div class="form-group col-md-4">
                                            <label for="total_video">Total Videos</label>
                                            <input type="number" class="form-control" id="total_video" name="total_video"
                                                value="{{ $rawTiktokAccount->total_video }}" placeholder="Total Videos" readonly>
                                        </div>

                                        <!-- Average Views -->
                                        <div class="form-group col-md-4">
                                            <label for="avg_views">Average Views</label>
                                            <input type="number" class="form-control" id="avg_views" name="avg_views"
                                                value="{{ $rawTiktokAccount->avg_views }}" placeholder="Average Views" readonly>
                                        </div>

                                        <!-- Engagement Rate -->
                                        <div class="form-group col-md-4">
                                            <label for="engagement_rate">Engagement Rate (%)</label>
                                            <input type="number" step="0.01" class="form-control" id="engagement_rate"
                                                name="engagement_rate" value="{{ round(($rawTiktokAccount->total_interactions / $rawTiktokAccount->avg_views) * 100, 2) }}"
                                                placeholder="Engagement Rate">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="form-group col-md-4">
                                            <label for="status_call">Status Call</label>
                                            <select class="form-control" id="status_call" name="status_call">
                                                <option value="pending"
                                                    {{ $rawTiktokAccount->status_call == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="response"
                                                    {{ $rawTiktokAccount->status_call == 'response' ? 'selected' : '' }}>
                                                    Response</option>
                                                <option value="no_response"
                                                    {{ $rawTiktokAccount->status_call == 'no_response' ? 'selected' : '' }}>No
                                                    Response</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="whatsapp_number">WhatsApp Number</label>
                                            <input type="text" class="form-control" id="whatsapp_number"
                                                name="whatsapp_number" value="{{ $rawTiktokAccount->whatsapp_number }}"
                                                placeholder="WhatsApp Number">
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes">{{ $rawTiktokAccount->notes }}</textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="file">File</label>
                                        <input type="file" class="form-control" id="file" name="file">
                                        @if ($rawTiktokAccount->file)
                                            <small>Current File: {{ $rawTiktokAccount->file }}</small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Update Account</button>
                                        <a href="{{ route('kol.type_influencer') }}"
                                            class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
