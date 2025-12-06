<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $fillable = ['username','password','Studentname','StudentID','Class'];
    protected $hidden = ['password'];
}