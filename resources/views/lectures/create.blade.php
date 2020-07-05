@extends('layouts.app')

@section('title')
    Mark Attendance | Attendance Base
@endsection

@push('styles')
    <style>
        #qrcode-container svg{
            display:block;
            width: 100%;
            margin:auto;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        @include('includes.header')
       <div class="row mt-5">
           @include('layouts.side-nav')
           <div class="col-md-9">
                <h4>Mark Attendance</h4>
                <hr>
                @include('includes.flash')
                <div id="loader" class="m-auto text-center" style="display:none;" >
                    <img src="{{ asset("images/loader.svg") }}" alt="Loading...">
                </div>

                @if ($course->students()->count())
                    <form id="form" action="{{ route("class.lecture.store", $course->id) }}" method="post">
                        @csrf
                        <div id="error-messages-attendance" class="alert alert-danger" style="display: none;"></div>
                        <div class="form-group">
                            <label for="date">Date (required)</label>
                            <input id="date" required type="date" class="form-control" name="conducted_at" value="{{ old("conducted_at") }}" required>
                        </div>
                        <div class="form-group">
                            <label for="note">Lecture Notes</label>
                            <textarea name="note" id="note" rows="4" class="form-control" name="note">{{ old("note") }}</textarea>
                        </div>
                        <hr>
                        <div class="form-group d-flex justify-content-around align-items-center" id="attendance-options">
                            <button class="btn btn-primary" id="generate-qr-code" type="button">
                                <img src="{{ asset("images/qr-code.svg") }}" alt="Mark Attendance" width="22px" class="mr-1">
                                Generate QR Code
                            </button>
                            OR
                            <button class="btn btn-secondary" id="mark-attendance-manually" type="button">
                                <svg class="bi bi-pencil-square mr-1" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                                Mark Manually
                            </button>
                        </div>
                        <div class="d-none" id="manual-attendance-section">
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
                                                <input type="checkbox" name="attendance[]" value="{{ $student->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        This class has no enrolled students. <a href="{{ route("class.student.create", $course->id) }}">Click here</a> to add students to this class.
                    </div>
                @endif
           </div>
       </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="qrcodeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="qrcodeModalLabel">Scan QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="qrcode-container">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
     {{-- manual-attendance-section --}}
     <script>
        var attendanceOptions = document.getElementById("attendance-options");
        var manualAttendanceSection = document.getElementById("manual-attendance-section");
        var generateQRCodeBtn = document.getElementById("generate-qr-code");
        var markManualAttendanceBtn = document.getElementById("mark-attendance-manually");
        var errorMessagesAttendance = document.getElementById("error-messages-attendance");
        var redirectUrl = '#';

        markManualAttendanceBtn.addEventListener("click", function(e){
            attendanceOptions.classList.remove("d-flex");
            attendanceOptions.classList.add("d-none");
            manualAttendanceSection.classList.remove("d-none");
         });

         generateQRCodeBtn.addEventListener("click", function(e){
            var formData = new FormData();
            formData.append("conducted_at", document.getElementById("date").value);
            formData.append("note", document.getElementById("note").value);
            var loader = document.getElementById("loader");
            var errorMessagesAttendance = document.getElementById("error-messages-attendance");

            var xhr;

            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            } else {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }

            // Holds the status of the XMLHttpRequest.
            // 0: request not initialized
            // 1: server connection established
            // 2: request received
            // 3: processing request
            // 4: request finished and response is ready
            // console.log(this);
            xhr.onreadystatechange = function() {
                errorMessagesAttendance.style.display = "none";
                errorMessagesAttendance.innerHTML = "";
                form.style.display = "none";
                loader.style.display = "block";
                if (this.readyState == 4 && this.status == 201) {
                    var data = JSON.parse(this.responseText);
                    redirectUrl = data.redirectUrl;
                    document.getElementById("qrcode-container").innerHTML = data.qrcode;
                    $('#qrcodeModal').modal({show:true})
                } else if (this.readyState == 4 && this.status == 422) {
                    var data = JSON.parse(this.responseText);
                    var errors = data.errors;
                    form.style.display = "block";
                    loader.style.display = "none";
                    var ul = document.createElement("ul");
                    var keys = Object.keys(errors);
                    keys.forEach(key => {
                        var li = document.createElement("li");
                        li.innerHTML = "<li>" + errors[key][0] + "</li>";
                        ul.appendChild(li);
                    });
                    errorMessagesAttendance.appendChild(ul);
                    errorMessagesAttendance.style.display = "block";
                }
            };

            xhr.open("POST", "{{ route("class.lecture.generateQR", $course->id) }}");
            xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xhr.send(formData);
         });

         $("#qrcodeModal").on("hide.bs.modal", function(e){

            var xhr;

            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            } else {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }

            // Holds the status of the XMLHttpRequest.
            // 0: request not initialized
            // 1: server connection established
            // 2: request received
            // 3: processing request
            // 4: request finished and response is ready
            // console.log(this);
            xhr.onreadystatechange = function() {
                errorMessagesAttendance.style.display = "none";
                errorMessagesAttendance.innerHTML = "";
                form.style.display = "none";
                loader.style.display = "block";
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    redirectUrl = data.redirectUrl;
                    window.location.href = redirectUrl;
                }
            };

            xhr.open("GET", redirectUrl);
            xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xhr.send();
         });
     </script>

@endpush
