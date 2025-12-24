<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class UserPermissionException extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'category_id', 'can_read', 'can_write'];
}