<?php namespace App\Models;

use \CodeIgniter\Model;

class VentaModel extends Model {

    protected $table         = "venta";
    protected $primaryKey    = 'id_venta';
    protected $returnType    = 'array';
    protected $allowedFields = ['preference_id','payment_id','status','id_producto','qty'];

    public function get_detalle_venta($id){
        return $this->asArray()
            ->select('id_venta, payment_id, status, qty, title , img, price, (price*qty) as total')
            ->from("venta as v",true)
            ->join('producto as p', 'p.id_producto = v.id_producto', 'inner' )
            ->where('id_venta', $id)
            ->findAll();
    }

}