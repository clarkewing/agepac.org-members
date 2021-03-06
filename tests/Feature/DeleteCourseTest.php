<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteCourseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling()->signInUnsubscribed();
    }

    /** @test */
    public function testGuestCannotDeleteCourse()
    {
        Auth::logout();

        $this->deleteCourse(1)
            ->assertUnauthorized();
    }

    /** @test */
    public function testOnlyAuthorizedUserCanDeleteCourse()
    {
        $course = Course::factory()->create();

        $this->deleteCourse($course->id)
            ->assertForbidden();

        $this->signIn(User::find($course->user_id));

        $this->deleteCourse($course->id)
            ->assertSuccessful();
    }

    /** @test */
    public function testCourseMustExist()
    {
        $this->deleteCourse(999)
            ->assertNotFound();
    }

    /** @test */
    public function testCourseOwnerCanDeleteIt()
    {
        $course = Course::factory()->create(['user_id' => Auth::id()]);

        $this->assertDatabaseHas('courses', ['id' => $course->id]);

        $this->deleteCourse($course)
            ->assertNoContent();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /**
     * Send a request to delete the course.
     *
     * @param  \App\Models\Course|int  $course
     * @return \Illuminate\Testing\TestResponse
     */
    protected function deleteCourse($course)
    {
        return $this->deleteJson(route(
            'courses.destroy',
            $course
        ));
    }
}
