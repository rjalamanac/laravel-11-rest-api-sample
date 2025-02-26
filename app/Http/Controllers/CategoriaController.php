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
        return response()->json(Categoria::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/categorias",
     *     summary="Create a new categoria",
     *     tags={"Categoria"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","descripcion"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="descripcion", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Categoria created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $categoria = Categoria::create($request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
        ]));

        return response()->json($categoria, 201);
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
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria not found'], 404);
        }

        return response()->json($categoria, 200);
    }

    /**
     * @OA\Put(
     *     path="/categorias/{id}",
     *     summary="Update an existing categoria",
     *     tags={"Categoria"},
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
     *     @OA\Response(response=200, description="Categoria updated"),
     *     @OA\Response(response=404, description="Categoria not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria not found'], 404);
        }

        $categoria->update($request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]));

        return response()->json($categoria, 200);
    }

    /**
     * @OA\Delete(
     *     path="/categorias/{id}",
     *     summary="Delete a categoria",
     *     tags={"Categoria"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Categoria deleted"),
     *     @OA\Response(response=404, description="Categoria not found")
     * )
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria not found'], 404);
        }

        $categoria->delete();

        return response()->json(null, 204);
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
