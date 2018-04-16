<?php
/**
 * Session storage helper class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Storage;

use Brunelencantado\Storage\Contracts\StorageInterface;

class SessionStorage implements StorageInterface
{
    protected $storageKey = 'items';

    /**
     * @brief Constructor
     *
     * @param String $storageKey
     */
    public function __construct($storageKey = null)
    {
        if ($storageKey) {
            $this->storageKey = $storageKey;
        }

        if (!isset($_SESSION[$this->storageKey])) {
            $_SESSION[$this->storageKey] = [];
        }
    }

    /**
     * @brief Sets key => value store
     *
     * @param String $key
     * @param Mixed $value
     * @return Void
     */
    public function set($key, $value)
    {
		
        $_SESSION[$this->storageKey][$key] = serialize($value);
		
    }

    /**
     * @brief Gets store value by key
     *
     * @param String $key
     * @return Void
     */
    public function get($key)
    {
        if (!isset($_SESSION[$this->storageKey][$key])) {
            return null;
        }

        return unserialize($_SESSION[$this->storageKey][$key]);
    }

    /**
     * @brief Deletes a key value store by key
     *
     * @param String $key
     * @return Void
     */
    public function delete($key)
    {
        unset($_SESSION[$this->storageKey][$key]);
    }

    /**
     * @brief Deletes all key values in session
     *
     * @return Void
     */
    public function destroy()
    {
        unset($_SESSION[$this->storageKey]);
    }

    /**
     * @brief Returns all values as key => value array
     *
     * @return Array [ key => value ]
     */
    public function all()
    {
        $items = [];

        foreach ($_SESSION[$this->storageKey] as $key => $item) {
            $items[$key] = unserialize($item);
        }

        return $items;
    }
	


}
