<?php


namespace App\Models;


class NotificationDataModel extends \CodeIgniter\Model
{
    protected $table = "notificacion_data";
    protected $primaryKey = 'id_notificacion_data';
    protected $returnType     = 'array';
    protected $allowedFields = ["id_notificacion","id"];
}