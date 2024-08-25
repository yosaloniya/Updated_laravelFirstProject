<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('users.index', compact('data'));
    }
    public function info(Request $request)
    {
        $existingUser = User::where('user_id', $request->user_id)->where('deleted_at', null)->exists();
        if ($existingUser) {
            return back()->with('error', 'User_id already exists.');
        }
        $emailExists = User::where('email', $request->email)->where('deleted_at', null)->exists();
        
        if ($emailExists) {
            return back()->with('error', 'Email already exists.');
        }
        $data = new User();
        $data->user_id = $request->user_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->fname = $request->fname;
        $data->sex = $request->sex;
        $data->phone = $request->phone;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $data->image = $filename;
        }
        $data->address = $request->address;
        $data->role = $request->role;
        $data->access = json_encode($request->access);
        $data->dob = $request->dob;
        $data->doj = $request->doj;
        $data->status = $request->status;
        $data->password = $request->password;
        if ($data->save()) {
            return back()->with('success', 'User saved Successfully');
        } else {
            return back()->with('error', 'User not saved');
        }
    }
    public function data(Request $request)
    {
        $user = User::find($request->id);
        $user->access = json_decode($user->access);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $existingUser = User::where('id', '!=', $request->id)->where('user_id', $request->user_id)->where('deleted_at', null)->exists();
        if ($existingUser) {
            return back()->with('error', 'User_id already exists.');
        }
        $emailExists = User::where('id', '!=', $request->id)->where('email', $request->email)->where('deleted_at', null)->exists();
        
        if ($emailExists) {
            return back()->with('error', 'Email already exists.');
        }
        $data = User::find($request->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->fname = $request->fname;
        $data->sex = $request->sex;
        $data->phone = $request->phone;
        $data->role = $request->role;
        $data->access = json_encode($request->access);
        $data->dob = $request->dob;
        $data->doj = $request->doj;
        $data->status = $request->status;
        $data->address = $request->address;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;

        if ($request->hasfile('image')) {
            $oldImage = public_path( "../uploads/" . $data->image);
        if (file_exists($oldImage)) {
            unlink($oldImage);
        }
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $data->image = $filename;
        } else {
            $data->image = $request->img;
        }
        if ($data->update()) {
            return back()->with('success', 'User Updated Successfully');
        } else {
            return back()->with('error', 'User not Updated');
        }
    }

    public function delete($id)
    {
        $data = User::find($id);
        $path = public_path( "../uploads/" . $data->image);
        if (file_exists($path)) {
            unlink($path);
            $data->delete();
            return response()
        ->json([
            'success'=>true,
            'tr'=>'tr_'.$id
            ]);
        } else {
            $data->delete();
            return response()
        ->json([
            'success'=>true,
            'tr'=>'tr_'.$id
            ]);
        }
    }

    public function status($id)
    {
        $data = User::find($id);
        if ($data->status == 1) {
            $data->status = 0;
        } else {
            $data->status = 1;
        }
        if ($data->update()) {
            return back()->with('success', 'Status Updated Successfully');
        } else {
            return back()->with('error', 'Status not Updated');
        }
    }
}
