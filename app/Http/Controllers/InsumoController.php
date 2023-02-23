<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

/**
 * Class InsumoController
 * @package App\Http\Controllers
 */
class InsumoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-insumo|crear-insumo|editar-insumo|borrar-insumo,', ['only'=>['index']]);
        $this->middleware ('permission: crear-insumo', ['only'=>['store']]);
        $this->middleware ('permission: editar-insumo', ['only'=>['updateInsumos']]);
        $this->middleware ('permission: borrar-insumo', ['only'=>['eliminarInsumo']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insumos = Insumo::paginate();

            $insumo = Insumo::select('insumos.nombre','insumos.medidas','insumos.id','insumos.estado')
            
            ->get();
        
            $editarinsumo = Insumo::select(
                "insumos.nombre",
                "insumos.medidas",
                "insumos.estado",
                "insumos.id"
                
            )->get();

            
                
            return view('insumo.index', compact('insumos','editarinsumo','insumo'))
            ->with('i', (request()->input('page', 1) - 1) * $insumos->perPage());

            
        
    }


    public function updateInsumos(Request $request)
    {
        $validatedData =$request->validate([
            'nombre' => 'required|unique:insumos,nombre',
            'id_medidas' => 'required',
        ], [
            'nombre.unique' => 'No se puede crear 2 insumos con el mismo nombre',
        ]);
        $input = $request->all();

        $actualizar =Insumo::where('id', $input["id"])
            ->update([
                'nombre' => $input["nombre"],
                'medidas' => $input["id_medidas"],
                'estado' => $input["id_estado"]
            
            ]);
        return redirect()->route('insumos.index')
            ->with('success', 'Insumo update successfully');

    }


    public function anularInsumo(Request $request)
    {
        $input = $request->all();
        Insumo::where('id', $input["idanular"])
            ->update([
                'estado' => 0
            ]);
        return redirect()->route('insumos.index')
            ->with('success', 'Status insumo successfully');
    }


   
    // public function store(Request $request)
    // {
        
    //     //request()->validate(Insumo::$rules);
    //     $input=$request->all();
    //     $insumo = Insumo::create([
    //         "nombre"=>$input["nombre"],
    //         "medidas"=>$input["id_medidas"]
    //     ]);

    //     return redirect()->route('insumos.index')
    //         ->with('success', 'Insumo created successfully.');
    // }

    public function store(Request $request)
{
    $validatedData =$request->validate([
        'nombre' => 'required|unique:insumos,nombre',
        'id_medidas' => 'required',
    ], [
        'nombre.unique' => 'No se puede crear 2 insumos con el mismo nombre',
    ]);

    $insumo = Insumo::create([
        "nombre" => $request->nombre,
        "medidas" => $request->id_medidas
    ]);

    return redirect()->route('insumos.index')
        ->with('success', 'Insumo created successfully.');
}

    

   
    public function eliminarInsumo(Request $request)
    {
        
            $input = $request->all();
            $insumo = Insumo::find($input["ideliminar"]);
            
            if ($insumo->ordenes->count() > 0) {
                return redirect()->route('insumos.index')->with('error', 'No se puede eliminar el insumo porque está asociado a una orden de compra');
            }
            
            $insumo->delete();
        
            return redirect()->route('insumos.index')
                ->with('success', 'Insumo deleted successfully');
    
        // $input=$request->all();
        
        // $insumo = Insumo::find($input["ideliminar"])->delete();

        // return redirect()->route('insumos.index')
        //     ->with('success', 'Insumo deleted successfully');
    }
}
