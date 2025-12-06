<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderBook extends Model {
    protected $fillable = ['username','Studentname','StudentID','Bookname','Status','OrderedDate','ReturnedDate'];
    protected $primaryKey = 'OrderID';
}