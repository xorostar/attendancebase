@extends('layouts.app')

@section('title')
    Edit Course | Attendance Base
@endsection

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                <h4>Edit Class</h4>
                <hr>
                @include('includes.flash')
                <fieldset>
                    <legend>General Information</legend>
                    <form id="form" action="{{ route("class.update", [$course->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method("PATCH")
                        <div class="form-group">
                            <label for="image">Image</label>
                            @if ($course->image)
                                <img class="img-thumbnail w-25 d-block" src="{{ asset("storage/$course->image") }}" alt="">
                            @endif
                            <input id="image" type="file" class="form-control-file @error('image') is-invalid @enderror" name="image">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Class name (required)</label>
                            <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old("name") ?? $course->name }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="section" class="col-form-label">Section</label>
                            <input id="section" type="text" class="form-control @error('section') is-invalid @enderror" name="section" value="{{ old("section") ?? $course->section  }}">
                            @error('section')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject" class="col-form-label">Subject</label>
                            <input id="subject" type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" value="{{ old("subject") ?? $course->subject  }}">
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="room" class="col-form-label">Room</label>
                            <input id="room" type="text" class="form-control @error('room') is-invalid @enderror" name="room" value="{{ old("room") ?? $course->room  }}">
                            @error('room')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </form>
                </fieldset>
                <hr>
                <fieldset>
                    <legend>Danger Zone</legend>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <span>
                            <div>Archive this class</div>
                            <small>Mark this class as archived</small>
                        </span>
                        <a href="{{ route("class.toggleArchive", $course->id) }}" onclick="return confirm('Are you sure you want to archive this class?')" class="btn btn-danger btn-sm">Archive this class</a>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <span>
                            <div>Delete this class</div>
                            <small>Once you delete a class, there is no going back. Please be certain.</small>
                        </span>
                        <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this class permanently?')){document.getElementById('delete-course-{{ $course->id }}-form').submit();}"  class="btn btn-danger btn-sm">Delete this class</a>
                    </div>
                    <form action="{{ route("class.destroy", $course->id) }}"
                        id="delete-course-{{ $course->id }}-form" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </fieldset>
           </div>
       </div>
    </div>
@endsection
