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
        return Alumno::all();
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
        return Alumno::find($id);
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
