<?php

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Yajra\DataTables\DataTables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public $show_action = true;

    /**
     * Display a listing of the Roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Roles');
        if(Module::hasAccess($module->id)) {
            return View('la.roles.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Roles'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
	 * Show the form for creating a new role.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
     * Store a newly created role in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Roles", "create")) {
            $rules = Module::validateRules("Roles", $request);
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $role = Role::create(['name' => str_replace(" ", "_", strtolower(trim($request->name)))]);

            return redirect()->route(config('laraadmin.adminRoute') . '.roles.index');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Module::hasAccess("Roles", "view")) {
            $role = Role::findById($id);
            if(isset($role)) {
                $module = Module::get('Roles');
                $module->row = $role;

                return view('la.roles.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('role', $role);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("role"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Module::hasAccess("Roles", "edit")) {
            $role = Role::findById($id);
            if(isset($role)) {
                $module = Module::get('Roles');
                $module->row = $role;

                return view('la.roles.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('role', $role);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("role"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Roles", "edit")) {
            $rules = Module::validateRules("Roles", $request, true);
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $role = Role::findById($id);
            $role->name = str_replace(" ", "_", strtolower(trim($request->name)));
            $role->save();

            return redirect()->route(config('laraadmin.adminRoute') . '.roles.index');
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Roles", "delete")) {
            Role::findById($id)->delete();
			
			// Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.roles.index');
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
        $values = DB::table('roles')->select(['id', 'name', 'guard_name'])->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($listing_cols); $j++) { 
				$col = $listing_cols[$j];
				if($fields_popup[$col] != null && Str::startsWith($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				$data->data[$i][1] = '<a href="'.url(config('laraadmin.adminRoute') . '/roles/'.$data->data[$i][0]).'">'.$data->data[$i][1].'</a>';
			}
			if($this->show_action) {
				$output = '<a href="'.url(config('laraadmin.adminRoute') . '/roles/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>';
			}
			
			if(Module::hasAccess("Roles", "delete")) {
				$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.roles.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
				$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
				$output .= Form::close();
			}
			$data->data[$i][] = (string)$output;
		}
        $out->setData($data);
        return $out;
    }

    /**
     * Save module role permissions (not implemented with Spatie directly, needs custom implementation if required).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save_module_role_permissions(Request $request, $id)
	{
		if(auth()->user()->hasRole('SUPER_ADMIN')) {
			$role = Spatie\Permission\Models\Role::findById($id);

			$modules_arr = DB::table('modules')->get();
			$modules_access = [];

			foreach ($modules_arr as $module_obj) {
				$module_obj->accesses = Module::getRoleAccess($module_obj->id, $id);
				$modules_access[] = $module_obj;
			}

			$now = now();

			foreach ($modules_access as $module) {
					
					/* =============== role_module_fields =============== */
		
				foreach ($module->accesses->fields as $field) {
					$field_name = $field['colname'] . '_' . $module->id . '_' . $role->id;
					$field_value = $request->$field_name;
					$access = ['0' => 'invisible', '1' => 'readonly', '2' => 'write'][$field_value] ?? 'invisible';

					$query = DB::table('role_module_fields')->where('role_id', $role->id)->where('field_id', $field['id']);

					if ($query->count() == 0) {
						DB::insert('insert into role_module_fields (role_id, field_id, access, created_at, updated_at) values (?, ?, ?, ?, ?)', [$role->id, $field['id'], $access, $now, $now]);
					} else {
						$query->update(['access' => $access]);
					}
				}

					/* =============== role_module =============== */
		
				$module_name = 'module_' . $module->id;
				$module_access = [
					'view' => $request->has('module_view_' . $module->id) ? 1 : 0,
					'create' => $request->has('module_create_' . $module->id) ? 1 : 0,
					'edit' => $request->has('module_edit_' . $module->id) ? 1 : 0,
					'delete' => $request->has('module_delete_' . $module->id) ? 1 : 0,
				];

				$query = DB::table('role_module')->where('role_id', $id)->where('module_id', $module->id);

				if ($query->count() == 0) {
					DB::insert('insert into role_module (role_id, module_id, acc_view, acc_create, acc_edit, acc_delete, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$id, $module->id, ...array_values($module_access), $now, $now]);
				} else {
					$query->update($module_access);
				}
			}

			return redirect(config('laraadmin.adminRoute') . '/roles/'.$id.'#tab-access');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

}
