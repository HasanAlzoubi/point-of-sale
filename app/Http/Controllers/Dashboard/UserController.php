<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users_create')->only('create');
        $this->middleware('permission:users_read')->only('index');
        $this->middleware('permission:users_update')->only(['edit', 'update']);
        $this->middleware('permission:users_delete')->only('destroy');
    }


    public function index(Request $request)
    {
        $users = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', '<>', 1)
            ->when($request->search, function ($query, $search) {
                    return $query->where(function ($query) use ($search) {
                        return $query->where('users.first_name', 'like', '%' . $search . '%')
                            ->orWhere('users.last_name', 'like', '%' . $search . '%');
                    });
                })->latest()->paginate(5);
        return view('dashboard.users.index', compact('users'));
    }

    public function show()
    {
        return view('page_errore.404');
    }


    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required | confirmed'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->image) {
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $data['image'] = $request->image->hashName();
        }

        $user = User::create($data);

        $user->attachRole('admin');

        if (!$request->permissions) {
            $user->syncPermissions([]);
        } else {
            $user->syncPermissions($request->permissions);
        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'image' => 'image',
            'email' => ['required', Rule::unique('users')->ignore($user->id)]

        ]);

        $data = $request->all();

        if ($request->image) {

            if ($user->image != 'default.png')
                File::delete(public_path('uploads/user_images/' . $user->image));

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $data['image'] = $request->image->hashName();
        }

        $user->update($data);

        if (!$request->permissions) {
            $user->syncPermissions([]);
        } else {
            $user->syncPermissions($request->permissions);
        }
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    public function destroy(User $user)
    {
        if ($user->image && $user->image != 'default.png') {
            File::delete(public_path('uploads/user_images/' . $user->image));
        }
        $user->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
