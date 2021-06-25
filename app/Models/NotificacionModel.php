<?php


namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table = "notificacion";
    protected $primaryKey = 'id_notificacion';
    protected $returnType     = 'array';
    protected $allowedFields = ["id","live_mode","type","date_created","application_id","user_id","version","api_version","action"];


}