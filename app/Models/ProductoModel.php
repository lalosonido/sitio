<?php namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = "producto";
    protected $primaryKey = 'id_producto';
    protected $returnType     = 'array';
    protected $allowedFields = ['img', 'title', 'price'];
}