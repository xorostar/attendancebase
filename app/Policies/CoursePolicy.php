<?php

namespace App\Policies;

use App\Course;
use App\Teacher;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Teacher  $teacher
     * @return mixed
     */
    public function viewAny(Teacher $teacher)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Teacher  $teacher
     * @param  \App\Course  $course
     * @return mixed
     */
    public function view(Teacher $teacher, Course $course)
    {
        return $teacher->id === $course->teacher_id
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Teacher  $teacher
     * @return mixed
     */
    public function create(Teacher $teacher)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Teacher  $teacher
     * @param  \App\Course  $course
     * @return mixed
     */
    public function update(Teacher $teacher, Course $course)
    {
        return $teacher->id === $course->teacher_id
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Teacher  $teacher
     * @param  \App\Course  $course
     * @return mixed
     */
    public function delete(Teacher $teacher, Course $course)
    {
        return $teacher->id === $course->teacher_id
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Teacher  $teacher
     * @param  \App\Course  $course
     * @return mixed
     */
    public function restore(Teacher $teacher, Course $course)
    {
        return $teacher->id === $course->teacher_id
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Teacher  $teacher
     * @param  \App\Course  $course
     * @return mixed
     */
    public function forceDelete(Teacher $teacher, Course $course)
    {
        return $teacher->id === $course->teacher_id
    }
}
