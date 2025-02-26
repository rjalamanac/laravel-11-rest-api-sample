<?php
namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Actividad;
use Illuminate\Http\Request;

/**
  * @OA\Tag(
  *     name="Alumno",
  *     description="API Endpoints of Alumno"
  * )
  */
class AlumnoController extends Controller
{
        /**
     * @OA\Get(
     *     path="/alumnos",
     *     summary="Get all alumnos",
     *     tags={"Alumno"},
     *     @OA\Response(response=200, description="List of alumnos")
     * )
     */
    public function index()
    {
        return response()->json(Alumno::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/alumnos",
     *     summary="Create a new alumno",
     *     tags={"Alumno"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "apellidos", "nombre_responsable", "apellido_responsable", "email_responsable", "telefono_responsable"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="apellidos", type="string"),
     *             @OA\Property(property="nombre_responsable", type="string"),
     *             @OA\Property(property="apellido_responsable", type="string"),
     *             @OA\Property(property="email_responsable", type="string", format="email"),
     *             @OA\Property(property="telefono_responsable", type="string", maxLength=15)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Alumno created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nombre_responsable' => 'required|string|max:255',
            'apellido_responsable' => 'required|string|max:255',
            'email_responsable' => 'required|email|unique:alumnos,email_responsable',
            'telefono_responsable' => 'required|string|max:15',
        ]);

        $alumno = Alumno::create($data);

        return response()->json($alumno, 201);
    }

    /**
     * @OA\Get(
     *     path="/alumnos/{id}",
     *     summary="Get a single alumno",
     *     tags={"Alumno"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Alumno details"),
     *     @OA\Response(response=404, description="Alumno not found")
     * )
     */
    public function show($id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json(['message' => 'Alumno not found'], 404);
        }

        return response()->json($alumno, 200);
    }

    /**
     * @OA\Put(
     *     path="/alumnos/{id}",
     *     summary="Update an existing alumno",
     *     tags={"Alumno"},
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
     *             @OA\Property(property="apellidos", type="string"),
     *             @OA\Property(property="nombre_responsable", type="string"),
     *             @OA\Property(property="apellido_responsable", type="string"),
     *             @OA\Property(property="email_responsable", type="string", format="email"),
     *             @OA\Property(property="telefono_responsable", type="string", maxLength=15)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Alumno updated"),
     *     @OA\Response(response=404, description="Alumno not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json(['message' => 'Alumno not found'], 404);
        }

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellidos' => 'sometimes|string|max:255',
            'nombre_responsable' => 'sometimes|string|max:255',
            'apellido_responsable' => 'sometimes|string|max:255',
            'email_responsable' => 'sometimes|email|unique:alumnos,email_responsable,' . $id,
            'telefono_responsable' => 'sometimes|string|max:15',
        ]);

        $alumno->update($data);

        return response()->json($alumno, 200);
    }

    /**
     * @OA\Delete(
     *     path="/alumnos/{id}",
     *     summary="Delete an alumno",
     *     tags={"Alumno"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Alumno deleted"),
     *     @OA\Response(response=404, description="Alumno not found")
     * )
     */
    public function destroy($id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json(['message' => 'Alumno not found'], 404);
        }

        $alumno->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/alumnos/{alumnoId}/actividades",
     *     summary="Get all activities for a specific student",
     *     tags={"Alumno"},
     *     @OA\Parameter(
     *         name="alumnoId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of activities")
     * )
     */
    public function getActividades($alumnoId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        return response()->json($alumno->actividades);
    }

    /**
     * @OA\Post(
     *     path="/alumnos/{alumnoId}/actividades/{actividadId}",
     *     summary="Associate an activity with a student",
     *     tags={"Alumno"},
     *     @OA\Parameter(
     *         name="alumnoId",
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
    public function associateActividad(Request $request, $alumnoId, $actividadId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $actividad = Actividad::findOrFail($actividadId);
        $alumno->actividades()->attach($actividadId);
        return response()->json(['message' => 'Activity associated with student successfully.']);
    }

    /**
     * @OA\Delete(
     *     path="/alumnos/{alumnoId}/actividades/{actividadId}",
     *     summary="Remove association between an activity and a student",
     *     tags={"Alumno"},
     *     @OA\Parameter(
     *         name="alumnoId",
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
    public function disassociateActividad($alumnoId, $actividadId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $alumno->actividades()->detach($actividadId);
        return response()->json(['message' => 'Activity disassociated from student successfully.']);
    }
}
