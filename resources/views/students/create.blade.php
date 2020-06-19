@extends('layouts.app')

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                <h4>Enroll Student</h4>
                <hr>
                <form id="form" action="{{ route("class.student.store", $course->id) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name (required)</label>
                        <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old("name") }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="roll_no">Roll No.</label>
                        <input id="roll_no" type="text" class="form-control @error('roll_no') is-invalid @enderror" name="roll_no" value="{{ old("roll_no") }}">
                        @error('roll_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email (required)</label>
                        <input id="email" type="email" required class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old("email") }}" >
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No.</label>
                        <input id="contact_no" type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" value="{{ old("contact_no") }}">
                        @error('contact_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
           </div>
       </div>
    </div>
@endsection
