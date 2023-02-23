<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//agregamos
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-rol|crear-rol|editar-rol|borrar-rol,', ['only'=>['index']]);
        $this->middleware ('permission: crear-rol', ['only'=>['create,store']]);
        $this->middleware ('permission: editar-rol', ['only'=>['edit,update']]);
        $this->middleware ('permission: borrar-rol', ['only'=>['eliminarRol']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::paginate(5);
        $filtro = Role::all();
        return view('roles.index', compact('roles','filtro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view ('roles.crear', compact('permission'));
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'=>'required|unique:roles,name', 'permission'=>'required']);
        $role = Role::create(['name'=> $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $rolestado= Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

        return view('roles.edit',compact('role', 'permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
    $old_nombre = $role->nombre;

    $this->validate($request, [
        'name' => 'required|unique:roles,name,' . $role->id,
        'permission' => 'required',
    ]);

    $role->name = $request->input('name');
    $role->estado = $request->input('estado');
    $role->save();

    if ($request->input('name') !== $old_nombre) {
        $this->validate($request, [
            'name' => 'unique:roles,name,' . $role->id,
        ]);
    }

    $role->syncPermissions($request->input('permission'));

    return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminarRol(Request $request)
    {
        $input=$request->all();
        
        $role = Role::find($input["ideliminar"]);
        if($role->name =='Administrador'){
            $error = 'No se puede eliminar el rol de administrador.';
        return redirect()->route('roles.index')->with('error', $error);
        }
        
        
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol deleted successfully');
    }
}