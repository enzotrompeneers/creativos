<?php
/**
 * Config
 *
 * Gets config parameters
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Config;

class Config
{

    protected $db;

    public function __construct($db)
    {

        $this->db = $db;

    }

    public function getConfig($key)
    {

        $query = "SELECT valor FROM ".XNAME."_config WHERE clave = '{$key}' ";
        $sql = $this->db->record($query);

        return $sql['valor'];

    }

}


