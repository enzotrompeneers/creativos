<?php
/**
 * Creates payments
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Tpv;

use Brunelencantado\Database\DbInterface;

class Payments
{

    protected $db;

    /**
     * @brief Constructor
     *
     * @param DbInterface $db
     */
    public function __construct(DbInterface $db)
    {

        $this->db = $db;

    }
    
    /**
     * @brief Creates payment and saves to database
     *
     * @param Integer $id
     * @return String Link to payment page
     */
    public function createPayment($id)
    {

        // Get client/service details
        $query = "SELECT * FROM ".XNAME."_reservas WHERE id = {$id}";
        $sql = $this->db->record($query);

        $insert = [];
        $insert['nombre'] = $sql['nombre'];
        $insert['apellido'] = $sql['apellido'];
        $insert['matricula'] = $sql['matricula'];
        $insert['email'] = $sql['email'];
        $insert['cantidad'] = $sql['precio_final'];
        $insert['concepto'] = trad('plane_parking_servicio');
        $insert['codigo_seguridad'] = random_string(10);
        $insert['pagado'] = 0;
        $insert['fecha_creado'] = date('Y-m-d');
        $insert['reserva_id'] = $id;

        // Insert into db, create and save link
        $lastId = $this->db->insertQuery($insert, XNAME . '_pagos');
        $link = BASE_SITE . LANGUAGE . '/app/redsys/' . $insert['codigo_seguridad'] . '/' . $lastId . '/';
        $update = ['enlace' => $link];
        $this->db->updateQuery($update, XNAME . '_pagos', ['id = ' . $lastId]);

        return $link;

    }

    /**
     * @brief Get details of reservation by payment id
     *
     * @param Integer $id
     * @return Array
     */
    public function getReservationDetails($pagoId)
    {

        $query = "
                    SELECT res.* 
                    FROM ".XNAME."_pagos pag
                    LEFT OUTER JOIN ".XNAME."_reservas res
                        ON pag.reserva_id = res.id
                    WHERE pag.id = {$pagoId}";
        $sql = $this->db->record($query);

        $output = $sql;
        $output['nombre_completo'] = $sql['nombre'] . ' ' . $sql['apellido'];

        return $output;

    }

    /**
     * @brief Sets payment as paid
     *
     * @param Integer $id
     * @return Void
     */
    public function setAsPaid($id)
    {

        $today = date('Y-m-d');

        $query = "UPDATE ".XNAME."_pagos SET pagado = 1, fecha_pago = '{$today}' WHERE id = {$id}";
        $sql = $this->db->query($query);

        return $sql;
        
    }

}