@component('mail::message')
# Hi {{ $student->name }},

{{ $course->teachers[0]->name }} (<{{ $course->teachers[0]->email }}>) invited you to the class **{{ $course->name }}**

Invitation link: **{{ $student->invite_code }}** \
Download Mobile App: <{{ asset("/app.apk") }}>

Instructions:
* Download the mobile application apk from the above link
* Install the downloaded apk
* Navigate to the join class screen in the installed app by pressing the + icon on the top right corner of the application
* Enter your invite code and submit the form
* Congratulations! You should now be enrolled successfully and should be able to mark your attendance now.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
