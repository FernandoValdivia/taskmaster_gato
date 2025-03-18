<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID de la tarea"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Título de la tarea"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción de la tarea"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Estado de la tarea"
 *     ),
 *     @OA\Property(
 *         property="due_date",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de vencimiento de la tarea"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID del usuario al que pertenece la tarea"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la tarea"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la tarea"
 *     )
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}