<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('NUKE_FILE')) {
    die("You can't access this file directly...");
}

class Cache
{
    /**
     * The path to the cache file folder
     *
     * @var string
     */
    private $_cachepath = 'cache/';

    /**
     * The name of the default cache file
     *
     * @var string
     */
    private $_cachename = 'default';

    /**
     * The cache data of the default cache file
     *
     * @var array
     */
    private $cachedData = [];

    /**
     * The cache file extension
     *
     * @var string
     */
    private $_extension = '.php';

    /**
     * The cache file suffix
     *
     * @var string
     */
    private $_suffix = '<?php exit;?>';

    /**
     * The cache file salt
     *
     * @var string
     */
    private $_salt = '';

    /**
     * Default constructor
     *
     * @param string|array [optional] $config
     * @return void
     */
    public function __construct($config = null)
    {
        global $pn_salt;
        $this->_salt = $pn_salt;
        if (true === isset($config)) {
            if (is_string($config)) {
                $this->setCache($config);
            } elseif (is_array($config)) {
                $this->setCache($config['name']);
                $this->setCachePath($config['path']);
                $this->setExtension($config['extension']);
            }
        }
    }

    /**
     * Check whether data accociated with a key
     *
     * @param string $key
     * @return boolean
     */
    public function isCached($key)
    {
        $this->setCache($key);
        $this->cachedData = false;
        $this->cachedData = $this->_loadCache();

        if ($this->cachedData != false) {
            return isset($this->cachedData[$key]['data']);
        }
    }

    /**
     * Store data in the cache
     *
     * @param string $key
     * @param mixed $data
     * @param integer [optional] $expiration
     * @return object
     */
    public function store($key, $data, $expiration = 0)
    {
        $storeData = [
            'time' => _NOWTIME,
            'expire' => $expiration,
            'data' => $data,
        ];

        $this->setCache($key);

        $dataArray = $this->_loadCache();

        if (true === is_array($dataArray)) {
            $dataArray[$key] = $storeData;
        } else {
            $dataArray = [$key => $storeData];
        }

        $cacheData = phpnuke_serialize($dataArray);

        $this->_save_cache($this->getCacheDir(), $this->_suffix . $cacheData);

        return $this;
    }

    /**
     * Retrieve cached data by its key
     *
     * @param string $key
     * @param boolean [optional] $timestamp
     * @return string
     */
    public function retrieve($key, $timestamp = false)
    {
        if ($this->isCached($key)) {
            false === $timestamp ? ($type = 'data') : ($type = 'time');
            if (!isset($this->cachedData[$key][$type])) {
                return null;
            }
            return $this->cachedData[$key][$type];
        }
        return '';
    }

    /**
     * Retrieve all cached data
     *
     * @param boolean [optional] $meta
     * @return array
     */
    public function retrieveAll($meta = false)
    {
        if ($meta === false) {
            $results = [];
            $cachedData = $this->_loadCache();
            if ($cachedData) {
                foreach ($cachedData as $k => $v) {
                    $results[$k] = phpnuke_unserialize($v['data']);
                };
            }

            return $results;
        } else {
            return $this->_loadCache();
        }
    }

    /**
     * Erase cached entry by its key
     *
     * @param string $key
     * @return object
     */
    public function erase($key)
    {
        $cacheData = $this->_loadCache();
        if (true === is_array($cacheData)) {
            if (true === isset($cacheData[$key])) {
                unset($cacheData[$key]);
                $cacheData = phpnuke_serialize($cacheData);
                $this->_save_cache(
                    $this->getCacheDir(),
                    $this->_suffix . $cacheData
                );
            } else {
                throw new Exception("Error: erase() - Key '{$key}' not found.");
            }
        }
        return $this;
    }

    /**
     * Erase all expired entries
     *
     * @return integer
     */
    public function eraseExpired()
    {
        $cacheData = $this->_loadCache();
        if (true === is_array($cacheData)) {
            $counter = 0;
            foreach ($cacheData as $key => $entry) {
                if (
                    true ===
                    $this->_checkExpired($entry['time'], $entry['expire'])
                ) {
                    unset($cacheData[$key]);
                    $counter++;
                }
            }
            if ($counter > 0) {
                $cacheData = phpnuke_serialize($cacheData);
                $this->_save_cache(
                    $this->getCacheDir(),
                    $this->_suffix . $cacheData
                );
            }
            return $counter;
        }
    }

    /**
     * Erase all cached entries
     *
     * @return object
     */
    public function eraseAll()
    {
        $cacheDir = $this->getCacheDir();
        if (true === file_exists($cacheDir)) {
            $cacheFile = fopen($cacheDir, 'w');
            fclose($cacheFile);
        }
        return $this;
    }

    public function flush_caches()
    {
        $cachePath = $this->getCachePath();
        $cache_files = get_dir_list($cachePath, 'files');
        foreach ($cache_files as $cache_file) {
            if ($cache_file == '.htaccess' || $cache_file == 'index.html') {
                continue;
            }

            if (!unlink($cachePath . $cache_file)) {
                throw new Exception(
                    "the cache file $cache_file can not be deleted."
                );
            }
        }
    }

    /**
     * Load appointed cache
     *
     * @return mixed
     */
    private function _loadCache()
    {
        if (true === file_exists($this->getCacheDir())) {
            $file = phpnuke_get_url_contents(
                $this->getCacheDir(),
                true,
                false,
                true
            );
            $file = str_replace($this->_suffix, "", $file);
            $file = phpnuke_unserialize($file);
            return $file;
        } else {
            return false;
        }
    }

    private function _save_cache($filename, $data)
    {
        // In our example we're opening $filename in append mode.
        // The file pointer is at the bottom of the file hence
        // that's where $data will go when we fwrite() it.
        if (!($handle = fopen($filename, 'w'))) {
            echo "Cannot open file ($filename)";
            exit();
        }

        // Write $data to our opened file.
        if (fwrite($handle, $data) === false) {
            echo "Cannot write to file ($filename)";
            exit();
        }

        fclose($handle);
    }

    /**
     * Get the cache directory path
     *
     * @return string
     */
    public function getCacheDir()
    {
        if (true === $this->_checkCacheDir()) {
            $filename = $this->getCache();
            $filename = preg_replace(
                '/[^0-9a-z\.\_\-]/i',
                '',
                strtolower($filename)
            );
            return $this->getCachePath() .
                $this->_getHash($filename) .
                $this->getExtension();
        }
    }

    /**
     * Get the filename hash
     *
     * @return string
     */
    private function _getHash($filename)
    {
        return sha1($this->_salt . $filename);
    }

    /**
     * Check whether a timestamp is still in the duration
     *
     * @param integer $timestamp
     * @param integer $expiration
     * @return boolean
     */
    private function _checkExpired($timestamp, $expiration)
    {
        $result = false;
        if ($expiration !== 0) {
            $timeDiff = _NOWTIME - $timestamp;
            $result = $timeDiff > $expiration ? true : false;
        }
        return $result;
    }

    /**
     * Check if a writable cache directory exists and if not create a new one
     *
     * @return boolean
     */
    private function _checkCacheDir()
    {
        if (
            !is_dir($this->getCachePath()) &&
            !mkdir($this->getCachePath(), 0775, true)
        ) {
            throw new Exception(
                'Unable to create cache directory ' . $this->getCachePath()
            );
        } elseif (
            !is_readable($this->getCachePath()) ||
            !is_writable($this->getCachePath())
        ) {
            if (!chmod($this->getCachePath(), 0775)) {
                throw new Exception(
                    $this->getCachePath() . ' must be readable and writeable'
                );
            }
        }

        return true;
    }

    /**
     * Cache path Setter
     *
     * @param string $path
     * @return object
     */
    public function setCachePath($path)
    {
        $this->_cachepath = $path;
        return $this;
    }

    /**
     * Cache path Getter
     *
     * @return string
     */
    public function getCachePath()
    {
        return $this->_cachepath;
    }

    /**
     * Cache name Setter
     *
     * @param string $name
     * @return object
     */
    public function setCache($name)
    {
        $this->_cachename = $name;
        return $this;
    }

    /**
     * Cache name Getter
     *
     * @return void
     */
    public function getCache()
    {
        return $this->_cachename;
    }

    /**
     * Cache file extension Setter
     *
     * @param string $ext
     * @return object
     */
    public function setExtension($ext)
    {
        $this->_extension = $ext;
        return $this;
    }

    /**
     * Cache file extension Getter
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }
}
?>
