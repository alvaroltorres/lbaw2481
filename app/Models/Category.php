<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'category_id'; // Specify the primary key if different from 'id'
}
