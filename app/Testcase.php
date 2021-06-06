<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
    protected $fillable = ['module_id', 'summary','description','file'];
}
