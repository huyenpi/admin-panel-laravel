<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        session(['sub_module_active' => 'user.list']);

        $keyword = "";

        if ($request->input("keyword")) {
            $keyword = $request->input("keyword");
        }
        $users = User::where("full_name", "LIKE", "%{$keyword}%")->paginate(5);

        if ($request->input('status') == 'active') {
            $users = User::where([["full_name", "LIKE", "%{$keyword}%"], ["status", "=", "active"]])->paginate(5);
        }
        if ($request->input('status') == 'banned') {
            $users = User::where([["full_name", "LIKE", "%{$keyword}%"], ["status", "=", "banned"]])->paginate(5);
        }
        if ($request->input('status') == 'trash') {
            $users = User::onlyTrashed()->where("full_name", "LIKE", "%{$keyword}%")->paginate(5);
        }

        $activeUserNumber = User::where('status', '=', 'active')->count();
        $bannedUserNumber = User::where('status', '=', 'banned')->count();
        $trashedUserNumber = User::onlyTrashed()->count();
        $count = ['active' => $activeUserNumber, 'banned' => $bannedUserNumber, 'trash' => $trashedUserNumber];
        $actions = [
            'delete' => 'Xóa tạm thời'
        ];
        if ($request->input('status') == 'trash') {
            $actions = [
                'restore' => "Khôi phục",
                'force_delete' => 'Xóa vĩnh viễn'
            ];
        }
        return view('admin.user.list', compact("users", "count", "actions"));
    }


    function add()
    {
        session(['sub_module_active' => 'user.add']);
        $roles = Role::all();
        return view("admin.user.add", compact("roles"));
    }
    function store(Request $request)
    {
        if ($request->has("btn-add")) {
            $request->validate(
                [
                    'full_name' => 'required|string|max:100',
                    'username' => 'required|string|min:3|max:20|regex:/^\S*$/|unique:users',
                    'email' => 'required|string|email|max:50|unique:users',
                    'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                    'password_confirmation' => 'required',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có ít nhất :min ký tự',
                    'max' => ':attribute có tối đa :max ký tự',
                    'email.unique' => 'Email đã tồn tại trong hệ thống',
                    'email.email' => 'Email không đúng định dạng',

                ],
                [
                    'full_name' => 'Họ và tên',
                    'username' => 'Tên người dùng',
                    'email' => 'Email',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác nhận mật khẩu'
                ]
            );
            $data = [
                'full_name' => $request->input('full_name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ];
            if ($request->filled('role')) {
                $data['role_id'] = $request->input('role');
            }
            try {
                $user = User::create($data);
                $user->sendEmailVerificationNotification();
                return redirect('/admin/user/list')->with(['status' => 'success', 'message' => 'Đã thêm thành viên thành công']);
            } catch (\Exception $e) {
                return redirect('/admin/user/list')->with(['status' => 'fail', 'message' => 'Thêm thành viên không thành công']);
            }
        }
    }
    function deleteOne($id)
    {

        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();

            return redirect()->route('user.list')->with(['status' => 'success', 'message' => 'Đã xóa thành viên thành công!']);
        } else {
            return redirect()->route('user.list')->with(['status' => 'fail', 'message' => "Bạn không thể tự xóa chính mình ra khỏi hệ thống!"]);
        }

    }
    function forceDeleteOne($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();

        return redirect()->route('user.list')->with(['status' => 'success', 'message' => 'Đã xóa thành viên thành công!']);
    }
    function deleteMany(Request $request)
    {

        $ids = $request->ids;
        if (User::whereIn('id', $ids)->delete()) {
            return redirect()->route("user.list")->with(['status' => 'success', 'message' => "Đã xóa thành viên thành công!"]);

        } else {
            return redirect()->route("user.list")->with(['status' => 'fail', 'message' => "Xóa thành viên không thành công!"]);
        }
    }
    function forceDeleteMany(Request $request)
    {
        $ids = $request->ids;
        if (User::onlyTrashed()->whereIn('id', $ids)->forceDelete()) {
            return redirect()->route("user.list")->with(['status' => 'success', 'message' => "Đã xóa thành viên thành công!"]);

        } else {
            return redirect()->route("user.list")->with(['status' => 'fail', 'message' => "Xóa thành viên không thành công!"]);
        }
    }

    function multipleActions(Request $request)
    {
        $ids = $request->ids;

        if ($ids) {
            foreach ($ids as $key => $value) {
                if (Auth::id() == $value) {
                    unset($ids[$key]);
                }
            }

            if (!empty($ids)) {
                $action = $request->action;
                if ($action == "delete") {
                    return redirect()->route("user.delete_many", compact("ids"));
                } elseif ($action == "restore") {
                    return redirect()->route("user.restore", compact("ids"));
                } elseif ($action == "force_delete") {
                    return redirect()->route("user.force_delete_many", compact("ids"));
                }
                return redirect()->route("user.list")->with(['status' => 'fail', 'message' => 'Bạn cần chọn tác vụ để thao tác']);
            }
            return redirect()->route("user.list")->with(['status' => 'fail', 'message' => 'Bạn không thể thao tác trên tài khoản của bạn']);
        }
        return redirect()->route("user.list")->with(['status' => 'fail', 'message' => 'Bạn cần chọn ít nhất 1 bản ghi để thao tác']);
    }

    function restore(Request $request)
    {
        $ids = $request->ids;
        if (User::onlyTrashed()->whereIn('id', $ids)->restore()) {
            return redirect()->route("user.list")->with(['status' => 'success', 'message' => "Đã khôi phục thành viên thành công!"]);

        } else {
            return redirect()->route("user.list")->with(['status' => 'fail', 'message' => "Khôi phục thành viên không thành công!"]);
        }
    }
    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view("admin.user.edit", compact("user", "roles"));
    }
    function update(Request $request, $id)
    {
        if ($request->has("btn-edit")) {
            $rules = [];
            if ($request->filled('full_name')) {
                $rules['full_name'] = 'required|string|max:100';
            }

            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/';
            }

            $request->validate(
                $rules,
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có ít nhất :min ký tự',
                    'max' => ':attribute có tối đa :max ký tự',
                    'email.unique' => 'Email đã tồn tại trong hệ thống',
                    'email.email' => 'Email không đúng định dạng',

                ],
                [
                    'full_name' => 'Họ và tên',
                    'username' => 'Tên người dùng',
                    'email' => 'Email',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác nhận mật khẩu'
                ]

            );
        }
        $user = User::find($id);
        if ($request->filled("full_name")) {
            $user->full_name = $request->input("full_name");
        }

        if ($request->filled("role")) {
            $user->role_id = $request->input("role");
        }
        if ($request->filled("status")) {
            $user->status = $request->input("status");
        }

        if ($request->filled('password')) {
            $password = $request->input('password');
            $user->password = Hash::make($password);
        }
        if ($user->update()) {
            return redirect()->route("user.list")->with(["status" => "success", "message" => "Cập nhật thông tin thành công!"]);
        } else {
            return redirect()->route("user.list")->with(["status" => "fail", "message" => "Cập nhật thông tin thất bại!"]);
        }

    }
}
