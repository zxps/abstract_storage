<?php

namespace Storage\Connection;

use Storage\Permissions;

class Protocol
{
    const TYPE_FILE = 1;
    const TYPE_SOCKET = 2;
    const TYPE_URL = 3;
    const TYPE_DB = 4;

    private $permissions;
    private $type;
    private $spec;

    private $options = [];

    /**
     * @param $type Storage Protocol type
     * @param $spec Address
     * @param array $options Options
     * @throws ProtocolException
     */
    public function __construct($type, Permissions $permissions, $spec, array $options = [])
    {
        if (!in_array($type, self::getAvailableTypes())) {
            throw new ProtocolException('Undefined protocol type');
        }
        $this->type = $type;
        $this->permissions = $permissions;
        $this->spec = $spec;
        $this->options = $options;
    }

    /**
     * @param $name
     * @return mixed
     * @throws ProtocolException
     */
    public function getOption($name, $default = null)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        return $default;
    }

    /**
     * @return Permissions
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function is($type)
    {
        return $this->type == $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSpec()
    {
        return $this->spec;
    }

    public static function getAvailableTypes()
    {
        return array_values((new \ReflectionClass(__CLASS__))->getConstants());
    }
}