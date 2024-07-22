<?php

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Collective\Html\FormFacade as Form;

use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Helpers\LAHelper;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Auth;
use DB;
use File;
use Validator;
use Datatables;

use App\Models\Upload;

class UploadsController extends Controller
{
	public $show_action = true;
	
	public function __construct() {
		// for authentication (optional)
		$this->middleware('auth', ['except' => 'get_file']);
	}
	
	/**
	 * Display a listing of the Uploads.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        if (Auth::user()->can('view_uploads')) {
            return view('la.uploads.index', [
                'show_actions' => $this->show_action
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}
	
	/**
     * Get file
     *
     * @return \Illuminate\Http\Response
     */
    public function get_file($hash, $name)
    {
        $upload = Upload::where("hash", $hash)->first();
        
        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->name != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        if($upload->public || Auth::user()->can('view_uploads') || Auth::id() == $upload->user_id) {
        
        // Validate if Image is Public
        if(!$upload->public && !isset(Auth::user()->id)) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 2",
            ]);
        }

            
            $path = $upload->path;

            if(!File::exists($path))
                abort(404);
            
            // Check if thumbnail
            $size = Input::get('s');
            if(isset($size)) {
                if(!is_numeric($size)) {
                    $size = 150;
                }
                $thumbpath = storage_path("thumbnails/".basename($upload->path)."-".$size."x".$size);
                
                if(!File::exists($thumbpath)) {
                    // Create Thumbnail
                    LAHelper::createThumbnail($upload->path, $thumbpath, $size, $size, "transparent");
                }
                $path = $thumbpath;
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $download = Input::get('download');
            if(isset($download)) {
                return response()->download($path, $upload->name);
            } else {
                $response = FacadeResponse::make($file, 200);
                $response->header("Content-Type", $type);
            }
            
            return $response;
        } else {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 3"
            ]);
        }
    }

    /**
     * Upload files via DropZone.js
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_files()
    {
        
        if(Auth::user()->can('create_uploads')) {
			$input = Input::all();
			
			if(Input::hasFile('file')) {
				/*
				$rules = array(
					'file' => 'mimes:jpg,jpeg,bmp,png,pdf|max:3000',
				);
				$validation = Validator::make($input, $rules);
				if ($validation->fails()) {
					return response()->json($validation->errors()->first(), 400);
				}
				*/
				$file = Input::file('file');
				
				// print_r($file);
				
				
				$folder = storage_path('uploads');
				$filename = $file->getClientOriginalName();
				$date_append = date("Y-m-d-His-");
                $upload_success = $file->move($folder, $date_append.$filename);
				
				if( $upload_success ) {
                    $public = Input::get('public') ? true : false;
					$upload = Upload::create([
						"name" => $filename,
						"path" => $folder.DIRECTORY_SEPARATOR.$date_append.$filename,
						"extension" => pathinfo($filename, PATHINFO_EXTENSION),
						"caption" => "",
                        "hash" => strtolower(str_random(20)),
						"public" => $public,
                        "user_id" => Auth::id()
					]);
					return response()->json([
						"status" => "success",
						"upload" => $upload
					], 200);
				} else {
					return response()->json([
						"status" => "error"
					], 400);
				}
			} else {
				return response()->json('error: upload file not found.', 400);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			], 400);
		}
    }

    /**
     * Get all files from uploads folder
     *
     * @return \Illuminate\Http\Response
     */
    public function uploaded_files()
    {
		if(Module::hasAccess("Uploads", "view")) {
			$uploads = array();
	
			// print_r(Auth::user()->roles);
			if (Auth::user()->can('view_uploads')) {
				$uploads = Upload::all();
			} else {
				if(config('laraadmin.uploads.private_uploads')) {
					// Upload::where('user_id', 0)->first();
					$uploads = Auth::user()->uploads;
				} else {
					$uploads = Upload::all();
				}
			}
			$uploads2 = array();
			foreach ($uploads as $upload) {
				$u = (object) array();
				$u->id = $upload->id;
				$u->name = $upload->name;
				$u->extension = $upload->extension;
				$u->hash = $upload->hash;
				$u->public = $upload->public;
				$u->caption = $upload->caption;
				$u->user = $upload->user->name;
				
				$uploads2[] = $u;
			}
			
			// $folder = storage_path('/uploads');
			// $files = array();
			// if(file_exists($folder)) {
			//     $filesArr = File::allFiles($folder);
			//     foreach ($filesArr as $file) {
			//         $files[] = $file->getfilename();
			//     }
			// }
			// return response()->json(['files' => $files]);
			return response()->json(['uploads' => $uploads2]);
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Update Uploads Caption
     *
     * @return \Illuminate\Http\Response
     */
    public function update_caption()
    {
        if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$caption = Input::get('caption');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Auth::user()->can('edit_uploads')) {
	
					// Update Caption
					$upload->caption = $caption;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Upload not found"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Update Uploads Filename
     *
     * @return \Illuminate\Http\Response
     */
    public function update_filename()
    {
        if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$filename = Input::get('filename');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Auth::user()->can('edit_uploads')) {
	
					// Update Caption
					$upload->name = $filename;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Update Uploads Public Visibility
     *
     * @return \Illuminate\Http\Response
     */
    public function update_public()
    {
		if(Module::hasAccess("Uploads", "edit")) {
			$file_id = Input::get('file_id');
			$public = Input::get('public');
			if(isset($public)) {
				$public = true;
			} else {
				$public = false;
			}
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Auth::user()->can('edit_uploads')) {
	
					// Update Caption
					$upload->public = $public;
					$upload->save();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }

    /**
     * Remove the specified upload from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_file()
    {
        if(Module::hasAccess("Uploads", "delete")) {
			$file_id = Input::get('file_id');
			
			$upload = Upload::find($file_id);
			if(isset($upload->id)) {
				if($upload->user_id == Auth::user()->id || Auth::user()->can('edit_uploads')) {
	
					// Update Caption
					$upload->delete();
	
					return response()->json([
						'status' => "success"
					]);
	
				} else {
					return response()->json([
						'status' => "failure",
						'message' => "Unauthorized Access 1"
					]);
				}
			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Unauthorized Access"
			]);
		}
    }
}
