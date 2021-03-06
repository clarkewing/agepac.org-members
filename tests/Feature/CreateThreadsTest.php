<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withExceptionHandling()->signIn();
    }

    /** @test */
    public function testGuestCannotCreateThread()
    {
        Auth::logout();

        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));

        $this->postJson(route('threads.store'))
            ->assertUnauthorized();
    }

    /** @test */
    public function testUnsubscribedUserCannotCreateThread()
    {
        $this->signInUnsubscribed();

        $this->get(route('threads.create'))
            ->assertRedirect(route('subscription.edit'));

        $this->postJson(route('threads.store'))
            ->assertPaymentRequired();
    }

    /** @test */
    public function testNewUserMustFirstVerifyEmailBeforeCreatingThreads()
    {
        $this->signIn(
            User::factory()->unverifiedEmail()->create()
        );

        $this->get(route('threads.create'))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'Tu dois vérifier ton adresse email avant de pouvoir publier.');

        $this->post(route('threads.store'), Thread::factory()->raw())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'Tu dois vérifier ton adresse email avant de pouvoir publier.');
    }

    /** @test */
    public function testSubscribedUserCanCreateNewThreads()
    {
        $this->followingRedirects()
            ->publishThread(['title' => 'Some title', 'body' => 'This is the body.'])
            ->assertSee('Some title');

        $this->assertDatabaseHas('posts', ['body' => '<p>This is the body.</p>']);
    }

    /** @test */
    public function testNewThreadCreatesAThreadInitiatorPost()
    {
        $this->publishThread(['title' => 'Some title', 'body' => 'This is the body.']);

        $this->assertDatabaseHas('threads', ['title' => 'Some title']);
        $this->assertDatabaseHas('posts', [
            'body' => '<p>This is the body.</p>',
            'is_thread_initiator' => true,
        ]);
    }

    /** @test */
    public function testThreadRequiresATitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function testThreadRequiresABody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function testThreadRequiresAValidChannel()
    {
        Channel::factory()->count(2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function testThreadCantBeCreatedInAnArchivedChannel()
    {
        $archivedChannel = Channel::factory()->create(['archived' => true]);

        $this->publishThread(['channel_id' => $archivedChannel->id])
            ->assertSessionHasErrors('channel_id');

        $this->assertCount(0, $archivedChannel->threads);
    }

    /** @test */
    public function testThreadRequiresAUniqueSlug()
    {
        $existingThread = Thread::factory()->create(['title' => 'Foo Title']);

        $this->assertEquals('foo-title', $existingThread->fresh()->slug);

        $thread = $this->publishThread(['title' => $existingThread->title], true)->json();

        $this->assertEquals('foo-title-' . strtotime($thread['created_at']), $thread['slug']);
    }

    /** @test */
    public function testThreadWithATitleEndingInANumberShouldGenerateTheProperSlug()
    {
        $existingThread = Thread::factory()->create(['title' => 'Financials 2020']);

        $thread = $this->publishThread(['title' => $existingThread->title], true)->json();

        $this->assertEquals('financials-2020-' . strtotime($thread['created_at']), $thread['slug']);
    }

    /**
     * Submits a post request to publish a thread.
     *
     * @param  array  $overrides
     * @param  bool  $wantsJson
     * @return \Illuminate\Testing\TestResponse
     */
    public function publishThread(array $overrides = [], bool $wantsJson = false)
    {
        $thread = Thread::factory()->withBody()->raw($overrides);

        if ($wantsJson) {
            return $this->postJson(route('threads.store'), $thread);
        }

        return $this->post(route('threads.store'), $thread);
    }
}
