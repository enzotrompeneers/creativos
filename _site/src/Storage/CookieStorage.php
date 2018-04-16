<?php
/**
 * Cookie storage helper class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Storage;

use Brunelencantado\Storage\Contracts\StorageInterface;

class CookieStorage implements StorageInterface
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
        setcookie($this->storageKey . '_' . $key, serialize($value), time() + (86400 * 365), '/');
    }

    /**
     * @brief Gets store value from key
     *
     * @param String $key
     * @return Void
     */
    public function get($key)
    {
        if (!isset($_COOKIE[$this->storageKey . '_' . $key])) {
            return null;
        }

        return unserialize($_COOKIE[$this->storageKey . '_' . $key]);
    }

    /**
     * @brief Deletes a key value store by key
     *
     * @param String $key
     * @return Void
     */
    public function delete($key)
    {
        unset($_COOKIE[$this->storageKey . '_' . $key]);
		setcookie($this->storageKey . '_' . $key, '', time() - 3600);
    }

    /**
     * @brief Destroys cookie
     *
     * @return Void
     */
    public function destroy()
    {
        unset($_COOKIE[$this->storageKey]);
    }

    /**
     * @brief Returns all values as key => value array
     *
     * @return Array [ key => value ]
     */
    public function all()
    {
        $items = [];

        foreach ($_COOKIE as $key => $item) {
			
			$aCookie = explode('_', $key);
			
			if ($aCookie[0] == $this->storageKey) {
				
				$items[$key] = unserialize($item);
			
			}
        }

        return $items;
    }

    /**
     * @brief Checks if key exists in store
     *
     * @param String $key
     * @param Mixed $item
     * @return Boolean
     */
	public function hasItem($key, $item)
	{
		if (isset($_COOKIE[$this->storageKey . '_' . $key])) {
			
			return in_array($item, unserialize($_COOKIE[$this->storageKey . '_' . $key]));
			
		}
		
		
	}

}
