<?php

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Dwij\Laraadmin\Helpers\LAHelper;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
    public $listing_cols = ['id', 'name', 'display_name'];

    /**
     * Display a listing of the Permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Permissions');
        if (Module::hasAccess($module->id)) {
            $permissions = Permission::all();
            return view('la.permissions.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module' => $module,
                'permissions' => $permissions
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Module::hasAccess("Permissions", "create")) {
            return view('la.permissions.create');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Store a newly created permission in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Module::hasAccess("Permissions", "create")) {
            $rules = [
                'name' => 'required|unique:permissions,name',
                'display_name' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Permission::create($request->only(['name', 'display_name']));
            return redirect()->route(config('laraadmin.adminRoute') . '.permissions.index');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Module::hasAccess("Permissions", "view")) {
            $permission = Permission::findById($id);
            if (isset($permission)) {
                return view('la.permissions.show', [
                    'permission' => $permission,
                ]);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("permission"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Module::hasAccess("Permissions", "edit")) {
            $permission = Permission::findById($id);
            if (isset($permission)) {
                return view('la.permissions.edit', [
                    'permission' => $permission,
                ]);
            } else {
                return view('errors.404');
            }
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Module::hasAccess("Permissions", "edit")) {
            $rules = [
                'name' => 'required|unique:permissions,name,' . $id,
                'display_name' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }

            $permission = Permission::findById($id);
            $permission->update($request->only(['name', 'display_name']));
            return redirect()->route(config('laraadmin.adminRoute') . '.permissions.index');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Module::hasAccess("Permissions", "delete")) {
            Permission::findById($id)->delete();
            return redirect()->route(config('laraadmin.adminRoute') . '.permissions.index');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtajax()
    {
        $values = Permission::query();
		$out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i = 0; $i < count($data->data); $i++) {
            $data->data[$i][] = view('la.layouts.actions', [
                'module' => 'Permissions',
                'id' => $data->data[$i][0]
            ])->render();
if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Permissions", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/permissions/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Permissions", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.permissions.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
        }
        $out->setData($data);
        return $out;
    }
	public function save_permissions(Request $request, $id)
	{
		if (Module::hasAccess("Roles", "edit")) {
			$role = Role::findById($id);
			if (isset($role)) {
				$permissions = $request->input('permissions', []);
				$role->syncPermissions($permissions);
				return redirect()->route(config('laraadmin.adminRoute') . '.permissions.index')
								->with('success', 'Permissions updated successfully.');
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("role"),
				]);
			}
				return redirect(config('laraadmin.adminRoute') . '/permissions/'.$id."#tab-access");
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
}
