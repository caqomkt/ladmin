<?php


namespace Dwij\Laraadmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

use Dwij\Laraadmin\Models\Menu;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Dwij\Laraadmin\Models\ModuleFieldTypes;
use Dwij\Laraadmin\Helpers\LAHelper;

/**
 * Class MenuController
 * @package Dwij\Laraadmin\Controllers
 *
 * Works after managing Menus and their hierarchy
 */
class MenuController extends Controller
{
    public function __construct()
    {
        // for authentication (optional)
        // $this->middleware('auth');
    }

    /**
     * Display a listing of Menus
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();

        // Pega menus raiz
        $menuItems = Menu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();

        // Separa por topnav
        $menusHeader = $menuItems->filter(function ($m) {
            return (int) $m->topnav === 1;
        });
        $menusSidebar = $menuItems->filter(function ($m) {
            return (int) $m->topnav !== 1;
        });

        // Opcional: menus não exibidos
        $menusNaoExibidos = Menu::whereNull('url')->get();

        return view('la.menus.index', [
            'menus' => $menuItems,
            'modules' => $modules,
            'menusHeader' => $menusHeader,
            'menusSidebar' => $menusSidebar,
            'menusNaoExibidos' => $menusNaoExibidos,
        ]);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $url = $request->input('url');
        $icon = $request->input('icon');
        $type = $request->input('type');
        $topnav = $request->input('topnav', 0);

        if ($type == "module") {
            $module_id = $request->input('module_id');
            $module = Module::find($module_id);
            if (isset($module->id)) {
                $name = $module->name;
                $url = $module->name_db;
                $icon = $module->fa_icon;
            } else {
                return response()->json(["status" => "failure", "message" => "Module not found"], 200);
            }
        }

        Menu::create([
            "name" => $name,
            "url" => $url,
            "icon" => $icon,
            "type" => $type,
            "parent" => 0,
            "topnav" => $topnav
        ]);

        return $type == "module"
            ? response()->json(["status" => "success"], 200)
            : redirect(config('laraadmin.adminRoute') . '/la_menus');
    }

    public function update_topnav(Request $request)
    {
        $menu = Menu::findOrFail($request->id);
        $menu->topnav = (int) $request->topnav;
        $menu->save();

        return response()->json(['status' => 'success']);
    }


    /**
     * Update Custom Menu
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $menu->name = $request->input('name');
        $menu->url  = $request->input('url');
        $menu->icon = $request->input('icon');
        $menu->type = $request->input('type', 'custom');

        // NOVO: posição (hierarchy)
        $hierarchy = (int) $request->input('hierarchy', 1);
        if ($hierarchy < 1) $hierarchy = 1;
        $menu->hierarchy = $hierarchy;

        // NOVO: topnav (vindo do editor)
        if ($request->has('topnav_edit')) {
            $menu->topnav = (int) $request->input('topnav_edit') === 1 ? 1 : 0;
            // quando muda topnav, parent fica 0 (raiz) a menos que você controle árvore por drag/drop
            if ($menu->parent != 0) $menu->parent = 0;
        }

        $menu->save();

        // Normaliza posições dentro do mesmo agrupamento parent/topnav
        $this->normalizeHierarchy($menu->parent, $menu->topnav);

        return redirect(config('laraadmin.adminRoute') . '/la_menus')
            ->with('success', 'Item atualizado.');
    }

    /**
     * Garante ordem 1..n por grupo (parent/topnav)
     */
    private function normalizeHierarchy(int $parentId, int $topnav): void
    {
        $siblings = Menu::where('parent', $parentId)
            ->where('topnav', $topnav)
            ->orderBy('hierarchy', 'asc')
            ->get();

        $pos = 1;
        foreach ($siblings as $s) {
            if ((int)$s->hierarchy !== $pos) {
                $s->hierarchy = $pos;
                $s->save();
            }
            $pos++;
        }
    }

    /**
     * Remove the specified Menu from database
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::find($id)->delete();

        // Redirecting to index() method for Listing
        return redirect()->route(config('laraadmin.adminRoute') . '.la_menus.index');
    }

    /**
     * Update Menu Hierarchy
     *
     * @return mixed
     */
    public function update_hierarchy(Request $request)
    {
        $parents = $request->input('jsonData', []);
        $parent_id = 0;

        for ($i = 0; $i < count($parents); $i++) {
            $this->apply_hierarchy($parents[$i], $i + 1, $parent_id);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Save Menu hierarchy Recursively
     *
     * @param $menuItem Menu Item Array
     * @param $num Hierarchy number
     * @param $parent_id Parent ID
     */
    function apply_hierarchy($menuItem, $num, $parent_id)
    {
        // echo "apply_hierarchy: ".json_encode($menuItem)." - ".$num." - ".$parent_id."  <br><br>\n\n";
        $menu = Menu::find($menuItem['id']);
        $menu->parent = $parent_id;
        $menu->hierarchy = $num;
        $menu->save();

        // apply hierarchy to children if exists
        if (isset($menuItem['children'])) {
            for ($i = 0; $i < count($menuItem['children']); $i++) {
                $this->apply_hierarchy($menuItem['children'][$i], $i + 1, $menuItem['id']);
            }
        }
    }
}
