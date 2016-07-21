<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/07/16
 * Time: 11:12
 * https://github.com/piwik/component-ini/blob/master/src/IniWriter.php
 */
class WriteIni
{

    private static $_instance;

    public static function getInstance(){

        if (self::$_instance == null) {
            self::$_instance = new WriteIni('config.ini');
        }

        return self::$_instance;
    }
    /**
     * @var string
     */
    private $_filename;

    /**
     * WriteIni constructor.
     */
    public function __construct($fileName)
    {
        $this->_filename = $fileName;
    }


    /**
     * Writes an array configuration to a INI file.
     *
     * The array provided must be multidimensional, indexed by section names:
     *
     * ```
     * array(
     *     'Section 1' => array(
     *         'value1' => 'hello',
     *         'value2' => 'world',
     *     ),
     *     'Section 2' => array(
     *         'value3' => 'foo',
     *     )
     * );
     * ```
     *
     * @param array $config
     * @param string $header Optional header to insert at the top of the file.
     * @throws Exception
     */
    public function writeToFile(array $config, $header = '')
    {
        $ini = $this->writeToString($config, $header);
        if (!file_put_contents($this->_filename, $ini)) {
            throw new Exception(sprintf('Impossible to write to file %s', $this->_filename));
        }
    }
    /**
     * Writes an array configuration to a INI string and returns it.
     *
     * The array provided must be multidimensional, indexed by section names:
     *
     * ```
     * array(
     *     'Section 1' => array(
     *         'value1' => 'hello',
     *         'value2' => 'world',
     *     ),
     *     'Section 2' => array(
     *         'value3' => 'foo',
     *     )
     * );
     * ```
     *
     * @param array $config
     * @param string $header Optional header to insert at the top of the file.
     * @return string
     * @throws Exception
     */
    public function writeToString(array $config, $header = '')
    {
        $ini = $header;
        $sectionNames = array_keys($config);
        foreach ($sectionNames as $sectionName) {
            $section = $config[$sectionName];
            // no point in writing empty sections
            if (empty($section)) {
                continue;
            }
            if (! is_array($section)) {
                throw new Exception(sprintf("Section \"%s\" doesn't contain an array of values", $sectionName));
            }
            $ini .= "[$sectionName]\n";
            foreach ($section as $option => $value) {
                if (is_numeric($option)) {
                    $option = $sectionName;
                    $value = array($value);
                }
                if (is_array($value)) {
                    foreach ($value as $currentValue) {
                        $ini .= $option . '[] = ' . $this->encodeValue($currentValue) . "\n";
                    }
                } else {
                    $ini .= $option . ' = ' . $this->encodeValue($value) . "\n";
                }
            }
            $ini .= "\n";
        }
        return $ini;
    }
    private function encodeValue($value)
    {
        if (is_bool($value)) {
            return (int) $value;
        }
        if (is_string($value)) {
            return "\"$value\"";
        }
        return $value;
    }
}