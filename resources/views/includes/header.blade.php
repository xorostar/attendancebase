<div class="rounded-xl background-image position-relative" style="height:240px; background: url({{ $course->image ? asset("storage/$course->image") : asset('images/react-banner.png')}}) rgba(0,0,0,0.5);">
    <div class="container-fluid py-4 text-white d-flex flex-column justify-content-between h-100">
        <div class="d-flex justify-content-between">
            <div>
                <h1 class="mb-0 text-white" style="font-size: 36px;">
                    {{ $course->name }}
                </h1>
                <p class="lead">{{ $course->section ?? $course->section}}</p>
            </div>
            <a href="{{ route("class.lecture.create", $course->id) }}" data-toggle="tooltip" data-placement="bottom" title="Mark Attendance" style="height: fit-content;">
                <img src="{{ asset("images/qr-code.svg") }}" alt="Mark Attendance" width="36">
            </a>
        </div>
        <div class="d-flex justify-content-between">
            <span>By {{ $course->author->name }}</span>
            <a href="{{ route("class.edit", $course->id) }}" class="text-white small">
                Upload photo
            </a>
        </div>
    </div>
</div>
