@extends('layouts.app')

@section('title')
    View Attendance | Attendance Base
@endsection

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                <h4>Attendance</h4>
                <hr>
                @include('includes.flash')
                <p for="date"><strong>Date:</strong> {{ $lecture->conducted_at }}</p>
                <p for="note"><strong>Lecture Notes:</strong> {{ $lecture->note ?? "None" }}</p>
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
                                        @if ($lecture->students()->where('students.id', $student->id)->where("is_present", true)->count() > 0)
                                            Present
                                        @else
                                            Absent
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
