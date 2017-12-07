<?php
/**
 * 2008 - 2017 (c) HDClic
 *
 * MODULE PrestaBlog
 *
 * @author    HDClic <prestashop@hdclic.com>
 * @copyright Copyright (c) permanent, HDClic
 * @license   Addons PrestaShop license limitation
 * @version    4.0.3
 * @link    http://www.hdclic.com
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Addons
 * for all its modules is valid only once for a single shop.
 */

class SqlParse
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * @var array List of keywords which will be replaced in queries
     */
    protected $metadata = array();

    /**
     * @var array List of errors during last parsing
     */
    protected $errors = array();

    /**
     * @param Db $db
     */
    public function __construct(Db $db = null)
    {
        if (is_null($db)) {
            $db = Db::getInstance();
        }
        $this->db = $db;
    }

    /**
     * Set a list of keywords which will be replaced in queries
     *
     * @param array $data
     */
    public function setMetaData(array $data)
    {
        foreach ($data as $k => $v) {
            $this->metadata[$k] = $v;
        }
    }

    /**
     * Parse a SQL file and execute queries
     *
     * @param string $filename
     * @param bool $stop_when_fail
     */
    public function parseFile($filename, $stop_when_fail = true)
    {
        if (!file_exists($filename)) {
            throw new PrestashopInstallerException("File $filename not found");
        }

        return $this->parse(Tools::file_get_contents($filename), $stop_when_fail);
    }

    /**
     * Parse and execute a list of SQL queries
     *
     * @param string $content
     * @param bool $stop_when_fail
     */
    public function parse($content, $stop_when_fail = true)
    {
        $this->errors = array();

        $content = str_replace(array_keys($this->metadata), array_values($this->metadata), $content);
        $queries = preg_split('#;\s*[\r\n]+#', $content);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!$query) {
                continue;
            }

            if (!$this->db->execute($query)) {
                $this->errors[] = array(
                    'errno' => $this->db->getNumberError(),
                    'error' => $this->db->getMsgError(),
                    'query' => $query,
                );

                if ($stop_when_fail) {
                    return false;
                }
            }
        }

        return count($this->errors) ? false : true;
    }

    /**
     * Get list of errors from last parsing
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
