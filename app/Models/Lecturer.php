<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;
    protected $table = 'lecturers';

    protected $primaryKey = 'id';

    protected $fillable = ['staff_id','department_id','level_assigned','user_id'];
}
