<?php
namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Actividad;
use Illuminate\Http\Request;

/**
  * @OA\Tag(
  *     name="Categoria",
  *     description="API Endpoints of Categoria"
  * )
  */
class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categorias",
     *     summary="Get all categorias",
     *     tags={"Categoria"},
     *     @OA\Response(response=200, description="List of categorias")
     * )
     */
    public function index()
    {
        return Categoria::all();
    }

    /**
     * @OA\Get(
     *     path="/categorias/{id}",
     *     summary="Get a single categoria",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Categoria details"),
     *     @OA\Response(response=404, description="Categoria not found")
     * )
     */
    public function show($id)
    {
        return Categoria::find($id);
    }

    /**
     * @OA\Get(
     *     path="/categorias/{categoriaId}/actividades",
     *     summary="Get all activities for a specific category",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of activities")
     * )
     */
    public function getActividades($categoriaId)
    {
        $categoria = Categoria::findOrFail($categoriaId);
        return response()->json($categoria->actividades);
    }

    /**
     * @OA\Post(
     *     path="/categorias/{categoriaId}/actividades/{actividadId}",
     *     summary="Associate an activity with a category",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Activity associated successfully")
     * )
     */
    public function associateActividad(Request $request, $categoriaId, $actividadId)
    {
        $categoria = Categoria::findOrFail($categoriaId);
        $actividad = Actividad::findOrFail($actividadId);
        $categoria->actividades()->attach($actividadId);
        return response()->json(['message' => 'Activity associated with category successfully.']);
    }

    /**
     * @OA\Delete(
     *     path="/categorias/{categoriaId}/actividades/{actividadId}",
     *     summary="Remove association between an activity and a category",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Activity disassociated successfully")
     * )
     */
    public function disassociateActividad($categoriaId, $actividadId)
    {
        $categoria = Categoria::findOrFail($categoriaId);
        $categoria->actividades()->detach($actividadId);
        return response()->json(['message' => 'Activity disassociated from category successfully.']);
    }
}
