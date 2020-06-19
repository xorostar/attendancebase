@extends('layouts.app')

@section('title')
    Edit Attendance | Attendance Base
@endsection

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                <h4>Edit Attendance</h4>
                <hr>
                @include('includes.flash')
                <form id="form" action="{{ route("class.lecture.update", [$course->id, $lecture->id]) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="date">Date (required)</label>
                        <input id="date" type="date" class="form-control" name="conducted_at" value="{{ old("conducted_at") ?? $lecture->conducted_at }}" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Lecture Notes</label>
                        <textarea name="note" id="note" rows="4" class="form-control" name="note">{{ old("note") ?? $lecture->note }}</textarea>
                    </div>
                    <hr>
                    <div id="manual-attendance-section">
                        <div class="table-responsive-sm">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Roll No.</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Attendance</th>
                                    </tr>
                                </thead>
                                @foreach ($course->students as $key => $student)
                                    <tr>
                                        <th scope="row">{{ $key+1 }}</th>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <input type="checkbox" name="attendance[]" value="{{ $student->id }}"
                                                @if ($lecture->students()->where('students.id', $student->id)->where("is_present", true)->count() > 0)
                                                    checked
                                                @endif
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
