<?php
/**
 * File storage helper class
 *
 * @author Daniel Beard / BE Creativos <daniel@creativos.be>
 */

namespace Brunelencantado\Storage;

use Brunelencantado\Storage\Contracts\StorageInterface;

class FileStorage implements StorageInterface
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

        if (!file_exists("storage/{$this->storageKey}")) {
            mkdir("storage/{$this->storageKey}");
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
        file_put_contents("storage/{$this->storageKey}/{$key}", $value);
    }

    /**
     * @brief Gets store value from key
     *
     * @param String $key
     * @return Void
     */
    public function get($key)
    {
        if ($this->keyExists($key)) {
            return file_get_contents("storage/{$this->storageKey}/{$key}");
        }
    }

    /**
     * @brief Deletes a file store by key
     *
     * @param String $key
     * @return Void
     */
    public function delete($key)
    {
        if (!$this->keyExists($key)) {
            return;
        }

        unlink("storage/{$this->storageKey}/{$key}");
    }

    /**
     * @brief Delete all files
     *
     * @return Void
     */
    public function destroy()
    {
        $dir = opendir("storage/{$this->storageKey}");

        while (false !== ($item = readdir($dir))) {
            if (!in_array($item, ['.', '..'])) {
                unlink("storage/{$this->storageKey}/{$item}");
            }
        }
    }

    /**
     * @brief Returns all values as key => value array
     *
     * @return Array [ key => value ]
     */
    public function all()
    {
        $items = [];

        $dir = opendir("storage/{$this->storageKey}");

        while (false !== ($item = readdir($dir))) {
            if (!in_array($item, ['.', '..'])) {
                $items[$item] = file_get_contents("storage/{$this->storageKey}/{$item}");
            }
        }

        return $items;
    }

    /**
     * @brief Returns if key exists
     *
     * @param String $key
     * @return Boolean
     */
    protected function keyExists($key)
    {
        return file_exists("storage/{$this->storageKey}/{$key}");
    }
}
