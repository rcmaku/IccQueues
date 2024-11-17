<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function index()
    {
        $agents = User::all();

        return view('agent.index', compact('agents'));
    }

    public function create()
    {
        return view('agent.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',

        ]);
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email'=> $request->email,
            'password' => $request->password,

        ]);
        return redirect()->route('agent.index')
            ->with('success', 'Item created successfully.');
    }


        public function edit(string $id){
            $agent = User::findOrFail($id);
            return view('agent.edit', compact('agent'));
    }

    public function update( Request $request, string $id ){

        $agent = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'password' => 'required|string|min:6',
        ]);

        $agent-> update($request->all());

        return redirect()->route('agent.index')
            ->with('success', 'Item updated successfully.');
    }

    public function show(User $agent){
        return view('agent.show', compact('agent'));
    }

    public function destroy(User $agent){
        $agent->delete();
        return redirect()->route('agent.index')
            ->with('success', 'Entry deleted successfully.');
    }

}



