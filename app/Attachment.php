<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    //protected $table = 'attachments';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','name', 'ext', 'message_id'];
}
