<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    // use HasFactory;

    // Specify the table name if it's different from the default 'roles'
    protected $table = 'roles';

    // Define the fields that are mass assignable
    protected $fillable = [
        'name',        // Name of the role (e.g., 'Admin', 'Manager', 'Standard')
        'description', // Description of the role
    ];

    // Define the relationship between Role and User (One-to-Many)
    public function users()
    {
        return $this->hasMany(User::class);  // A role can have many users
    }
}
