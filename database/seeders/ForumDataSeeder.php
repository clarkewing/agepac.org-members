<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Channel;
use App\Models\Favorite;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadSubscription;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class ForumDataSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $this->channels()->content();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Seed the channels table.
     */
    protected function channels()
    {
        Channel::truncate();

        Channel::factory()->count(2)->create();
        Channel::factory()->count(2)->create(['parent_id' => Channel::factory()->create()->id]);
        Channel::factory()->count(3)->create(['parent_id' => Channel::factory()->create()->id]);
        Channel::factory()->count(4)->create(['parent_id' => Channel::factory()->create()->id]);

        return $this;
    }

    /**
     * Seed the thread-related tables.
     */
    protected function content()
    {
        Thread::truncate();
        Post::truncate();
        Attachment::truncate();
        Poll::truncate();
        (new Filesystem)->cleanDirectory('storage/app/public/attachments');
        ThreadSubscription::truncate();
        Favorite::truncate();

        Thread::factory()->count(30)->fromExistingChannelAndUser()->create()
            ->each(function ($thread) {
                $this->recordActivity($thread, 'created', $thread->creator->id);

                // Attach poll to thread in 15% of cases.
                if ($this->faker->boolean(15)) {
                    Poll::factory()->create(['thread_id' => $thread->id]);
                }

                Post::factory()->count($this->faker->numberBetween(1, 10))
                    ->fromExistingUser()
                    ->withAttachment($this->faker->boolean(10))
                    ->create([
                        'thread_id' => $thread->id,
                    ])->each(function ($post) {
                        $this->recordActivity($post, 'created', $post->owner->id);
                    });
            });
    }

    /**
     * @param $model
     * @param $event_type
     * @param $user_id
     *
     * @throws ReflectionException
     */
    protected function recordActivity($model, $event_type, $user_id)
    {
        $type = strtolower((new \ReflectionClass($model))->getShortName());

        $model->morphMany(Activity::class, 'subject')->create([
            'user_id' => $user_id,
            'type' => "{$event_type}_{$type}",
        ]);
    }
}
