@extends('layouts.app')

@section('title')
    People | Attendance Base
@endsection

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                @include('includes.flash')
                <h4>Teachers</h4>
                <hr>
                <ul class="list-group list-group-flush">
                    @foreach ($course->teachers as $teacher)
                    <li class="list-group-item text-dark rounded d-flex align-items-center">
                        <div class="rounded-circle d-inline-block mr-4" style="width:32px; height:32px; background-image:url({{ asset("images/avatar.png") }}); background-position: center; background-size: cover;
                        background-repeat:no-repeat;"></div>
                        <div class="h6 font-weight-normal">{{ $teacher->name }}</div>
                    </li>
                    @endforeach
                </ul>
                <br>
                <div class="d-flex align-items-center justify-content-between">
                    <h4>Students</h4>
                    <a href="{{ route("class.student.create", $course->id) }}" class="btn btn-link text-dark" data-toggle="tooltip" data-placement="bottom" title="Add student">
                        <svg class="bi bi-person-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M11 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM1.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zm9.974.056v-.002.002zM6 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm4.5 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                            <path fill-rule="evenodd" d="M13 7.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                        </svg>
                    </a>
                </div>
                <hr>
                <div class="list-group list-group-flush">
                    @foreach ($course->students as $student)
                    <div class="list-group-item text-dark rounded d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-inline-block mr-4" style="width:32px; height:32px; background-image:url({{ asset("images/avatar.png") }}); background-position: center; background-size: cover;
                            background-repeat:no-repeat;"></div>
                            <div class="h6 font-weight-normal">{{ $student->name }} ({{ $student->attendancePercentage() }})</div>
                        </div>
                        <div class="actions">
                            <a href="{{ route("class.student.edit", [$course->id, $student->id]) }}" class="btn btn-link text-secondary" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                <svg class="bi bi-pencil-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </a>
                            <a href="#" class="btn btn-link text-danger"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this student permanently?')){document.getElementById('delete-student-{{ $student->id }}-form').submit();}" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                <svg class="bi bi-trash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </a>
                            <form action="{{ route("class.student.destroy", [$course->id, $student->id]) }}"
                                id="delete-student-{{ $student->id }}-form" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="{{ route("class.student.resetDeviceLink", [$course->id, $student->id]) }}" class="btn btn-link text-dark" data-toggle="tooltip" data-placement="bottom" title="Reset Device">
                                <svg class="bi bi-arrow-repeat" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.854 7.146a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L2.5 8.207l1.646 1.647a.5.5 0 0 0 .708-.708l-2-2zm13-1a.5.5 0 0 0-.708 0L13.5 7.793l-1.646-1.647a.5.5 0 0 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0 0-.708z"/>
                                    <path fill-rule="evenodd" d="M8 3a4.995 4.995 0 0 0-4.192 2.273.5.5 0 0 1-.837-.546A6 6 0 0 1 14 8a.5.5 0 0 1-1.001 0 5 5 0 0 0-5-5zM2.5 7.5A.5.5 0 0 1 3 8a5 5 0 0 0 9.192 2.727.5.5 0 1 1 .837.546A6 6 0 0 1 2 8a.5.5 0 0 1 .501-.5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
           </div>
       </div>
    </div>
@endsection
