<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Product extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
  
    protected $fillable = ['name','detail','price', 'category_id','quantity'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}