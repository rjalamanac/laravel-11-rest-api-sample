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
        return response()->json(Actividad::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/actividades",
     *     summary="Create a new actividad",
     *     tags={"Actividad"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "descripcion"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="descripcion", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Actividad created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $actividad = Actividad::create($request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]));

        return response()->json($actividad, 201);
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
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad not found'], 404);
        }

        return response()->json($actividad, 200);
    }

    /**
     * @OA\Put(
     *     path="/actividades/{id}",
     *     summary="Update an existing actividad",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="descripcion", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Actividad updated"),
     *     @OA\Response(response=404, description="Actividad not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad not found'], 404);
        }

        $actividad->update($request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
        ]));

        return response()->json($actividad, 200);
    }

    /**
     * @OA\Delete(
     *     path="/actividades/{id}",
     *     summary="Delete an actividad",
     *     tags={"Actividad"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Actividad deleted"),
     *     @OA\Response(response=404, description="Actividad not found")
     * )
     */
    public function destroy($id)
    {
        $actividad = Actividad::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad not found'], 404);
        }

        $actividad->delete();

        return response()->json(null, 204);
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
