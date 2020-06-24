<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class)->withPivot('student_id', 'is_present', 'course_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attendancePercentage()
    {
        $totalLectures  = $this->lectures()->count();
        $totalPresents = $this->lectures()->where('is_present', true)->count();
        if (!$totalLectures) {
            return "Unmarked";
        } else {
            return ceil((($totalPresents / $totalLectures) * 100)) . "%";
        }
    }
}
