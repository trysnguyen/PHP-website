<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    protected $fillable = ['Bookname','Author','Category','Quantity'];
    protected $primaryKey = 'BookID';
}