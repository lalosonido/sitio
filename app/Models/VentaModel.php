<?php namespace App\Models;

use \CodeIgniter\Model;

class VentaModel extends Model {

    protected $table         = "venta";
    protected $primaryKey    = 'id_venta';
    protected $returnType    = 'array';
    protected $allowedFields = ['preference_id','payment_id','status'];

}