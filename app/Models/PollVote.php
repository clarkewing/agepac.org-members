<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'option_id',
    ];

    /**
     * Get the user that cast the vote.
     */
    public function voter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the option chosen.
     */
    public function option()
    {
        return $this->belongsTo(PollOption::class);
    }
}
