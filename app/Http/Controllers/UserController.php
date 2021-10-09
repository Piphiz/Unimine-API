<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->user->all();

        return response()->json(['data' => $user], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if ($this->user->where('email', '=', $data['email'])->first() && $data['email']  !== '' ) {
                return response()->json([],409);
            }

            $this->user->create($data);
            return response()->json([],201);
        } catch (\Exception $e) {
            return response()->json([],422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            return response()->json(['data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json([],409);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            if ($this->user->where('email', '=', $data['email'])->first() && $data['email']  !== '' ) {
                return response()->json([],409);
            }

            $user = $this->user->find($id);
            $user->update($data);
            return response()->json([],200);
        } catch (\Exception $e) {
            return response()->json([],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->user->findOrFail($id);
            $this->user->destroy($id);
            return response()->json([],200);
        } catch (\Exception $e) {
            return response()->json([],404);
        }
    }
}
