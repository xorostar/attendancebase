@extends('layouts.app')

@section('title')
    Profile | Attendance Base
@endsection

@section('content')
    <div class="container">
        @include('includes.flash')
       <div class="row justify-content-center">
           <div class="col-md-8">
               <div class="card">
                   <div class="card-header">
                        <h4>Profile</h4>
                   </div>
                    <div class="card-body">
                        <form id="form" action="{{ route("profile.update") }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PATCH")

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="profile-picture">Profile Picture</label>
                                @isset($user->profile_picture)
                                    <img src="{{ asset("storage/$user->profile_picture") }}" alt="" class="img-thumbnail d-block mb-1" style="width:125px">
                                @endisset
                                <input id="profile-picture" type="file" class="form-control-file @error('profile_picture') is-invalid @enderror" name="profile_picture">
                                @error('profile_picture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                        </form>
                    </div>
               </div>
           </div>
       </div>
    </div>
@endsection
