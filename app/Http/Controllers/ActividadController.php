<?php
namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Categoria;
use App\Models\Alumno;
use Illuminate\Http\Request;

/**
  * @OA\Tag(
  *     name="Actividad",
  *     description="API Endpoints of Actividad"
  * )
  */
class ActividadController extends Controller
{
    /**
     * @OA\Get(
     *     path="/actividades",
     *     summary="Get all actividades",
     *     tags={"Actividad"},
     *     @OA\Response(response=200, description="List of actividades")
     * )
     */
    public function index()
    {
        return Actividad::all();
    }

    /**
     * @OA\Get(
     *     path="/actividades/{id}",
     *     summary="Get a single actividad",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Actividad details"),
     *     @OA\Response(response=404, description="Actividad not found")
     * )
     */
    public function show($id)
    {
        return Actividad::find($id);
    }

    /**
     * @OA\Get(
     *     path="/actividades/{actividadId}/categorias",
     *     summary="Get all categories for a specific activity",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of categories")
     * )
     */
    public function getCategorias($actividadId)
    {
        $actividad = Actividad::findOrFail($actividadId);
        return response()->json($actividad->categorias);
    }

    /**
     * @OA\Get(
     *     path="/actividades/{actividadId}/alumnos",
     *     summary="Get all students for a specific activity",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of students")
     * )
     */
    public function getAlumnos($actividadId)
    {
        $actividad = Actividad::findOrFail($actividadId);
        return response()->json($actividad->alumnos);
    }

    /**
     * @OA\Post(
     *     path="/actividades/{actividadId}/categorias/{categoriaId}",
     *     summary="Associate a category with an activity",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category associated successfully")
     * )
     */
    public function associateCategoria(Request $request, $actividadId, $categoriaId)
    {
        $actividad = Actividad::findOrFail($actividadId);
        $categoria = Categoria::findOrFail($categoriaId);
        $actividad->categorias()->attach($categoriaId);
        return response()->json(['message' => 'Category associated with activity successfully.']);
    }

    /**
     * @OA\Delete(
     *     path="/actividades/{actividadId}/categorias/{categoriaId}",
     *     summary="Remove association between a category and an activity",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="actividadId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category disassociated successfully")
     * )
     */
    public function disassociateCategoria($actividadId, $categoriaId)
    {
        $actividad = Actividad::findOrFail($actividadId);
        $actividad->categorias()->detach($categoriaId);
        return response()->json(['message' => 'Category disassociated from activity successfully.']);
    }
}
