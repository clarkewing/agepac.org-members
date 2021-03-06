<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PollResultsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('members-area');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $channelSlug
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, string $channelSlug, Thread $thread)
    {
        $poll = $thread->poll;

        $this->authorize('viewResults', $poll);

        return Response::json(
            $poll->getResults($request->user()->can('viewVotes', $poll))
        );
    }
}
