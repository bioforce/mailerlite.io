<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;
use App\Http\Resources\SubscriberResource;
use App\Rules\DomainValidation;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return SubscriberResource::collection(Subscriber::with('fields')->paginate(10));//response()->json(Subscriber::with('fields')->paginate(10));
    }

    /**
     * Subscribe user form front
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:subscribers', 'email', new DomainValidation],
        ]);
        $subscriber = Subscriber::create($request->except(['_token', 'fields']));
        $subscriber->unconfirmed();
        $subscriber->fields()->sync($request->fields);
        return new SubscriberResource($subscriber);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:subscribers', 'email'],
        ]);
        $subscriber = Subscriber::create($request->except(['_token', 'fields']));
        $subscriber->confirmed();
        $subscriber->fields()->sync($request->fields);
        return new SubscriberResource($subscriber);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        //
        $request->validate([
            'email' => ['required', 'unique:subscribers,email,'.$subscriber->id, 'email'],
        ]);
        $subscriber->fields()->sync($request->filled);
        return response()->json($subscriber->update($request->except(['_token', 'fields'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        //
        return response()->json($subscriber->delete());
    }
}
