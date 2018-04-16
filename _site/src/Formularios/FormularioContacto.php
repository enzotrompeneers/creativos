<?php
/**
 * Form processing tool
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Formularios;

use \Brunelencantado\Database\DbInterface;

class FormularioContacto extends Formulario
{

	

    /**
     * @brief  Gets email history of client
     *
     * @param String $email
     * @return Void
     */
    public function getHistory($email)
    {

        $query = "SELECT * FROM ".XNAME."_contactos WHERE email = '{$email}' ORDER BY fecha DESC";
        $sql = $this->db->dataset($query);

        if ($sql){

            $output = '<ul>';

            foreach ($sql as $k => $v){

                $output .= '<li>' . $v['fecha'] . ' : <a href="' . $v['link'] . '">' . $v['link'] . '</a></li>';

            }

            $output .= '</ul>';

            return $output;

        }

    }


}


// End of file