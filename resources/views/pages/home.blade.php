@extends('layouts.app')

@section('title')
    Home | Attendance Base
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('includes.flash')
        </div>
        @forelse (auth()->user()->courses()->where('is_archived', false)->get() as $course)
            <div class="col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-header text-left text-white d-flex align-items-center background-image" style="background: url({{ $course->image ? asset("storage/$course->image") : asset('images/react-banner.png')}}) rgba(0,0,0,0.5); height:150px;">
                        <span>
                            <a href="{{ route("class.show", $course->id) }}" class="mb-1 text-white" style="font-size: 22px;">
                                @if (strlen($course->name) > 22)
                                    {{ \Illuminate\Support\Str::limit($course->name, 22, $end='...') }}
                                @else
                                    {{ $course->name }}
                                @endif
                            </a>
                            <p>
                                <div>{{ $course->section ?? $course->section}}</div>
                                <div>{{ $course->author->name }}</div>
                            </p>
                        </span>
                    </div>
                    <div class="card-footer text-muted bg-white d-flex justify-content-between">
                        <div>
                            {{ $course->students()->count() }} Student
                        </div>
                        <div>
                            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Mark Attendance">
                                <img src="{{ asset("images/qr-code-dark.svg") }}" alt="Mark Attendance" width="24">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <img src="{{ asset("images/students.jpg") }}" style="width:55vw;" class="d-block m-auto" alt="">
                <div class="text-center w-75 m-auto">
                    <p class="text-secondary">
                        You have no active classes, <a href="#" data-toggle="modal" data-target="#create-class-modal">click here</a> to create a new class or If you are looking for any of your older classes visit <a href="#">Archived classes</a>.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
