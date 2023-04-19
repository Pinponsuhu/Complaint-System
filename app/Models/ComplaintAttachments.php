<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAttachments extends Model
{
    use HasFactory;

    protected $table = 'complaint_attachments';

    protected $primaryKey = 'id';

    protected $fillable = ['complaint_id','attachment'];

}
