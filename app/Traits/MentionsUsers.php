<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Collection;
use Stevebauman\Purify\Facades\Purify;

trait MentionsUsers
{
    /**
     * Sets the body of the subject.
     *
     * @param  string  $body
     * @return void
     */
    public function setBodyAttribute(string $body): void
    {
        $this->attributes['body'] = $this->replaceMentions($body);
    }

    /**
     * Get the sanitized body.
     *
     * @param  string  $value
     * @return string
     */
    public function getBodyAttribute($value)
    {
        return Purify::clean($value);
    }

    /**
     * Returns an array of users mentioned in body.
     *
     * @param  string|null  $body
     * @return \Illuminate\Support\Collection
     */
    public function mentionedUsers(string $body = null): Collection
    {
        preg_match_all($this->mentionPattern(), $body ?? $this->body, $matches);

        return User::whereIn('username', $matches[1])->get();
    }

    /**
     * Returns the pattern used for matching usernames.
     *
     * @return string
     */
    protected function mentionPattern(): string
    {
        return '/\B@([a-z-]+\.[a-z-]+\b)/i';
    }

    /**
     * Replace mentions with a link to the mentioned user's profile.
     *
     * @param  string  $body
     * @return string
     */
    protected function replaceMentions(string $body): string
    {
        foreach ($this->mentionedUsers($body)->pluck('username') as $username) {
            $body = str_replace("@$username", "<a href=\"/profiles/$username\">@$username</a>", $body);
        }

        return $body;
    }
}
