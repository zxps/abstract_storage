<?php

namespace Storage;

class Permissions
{
    const READ = 1; // 1 << 0
    const WRITE = 2; // 1 << 1

    private $permission;

    public function __construct($permission)
    {
        $all = self::READ | self::WRITE;
        if (!($all & $permission )) {
            throw new \InvalidArgumentException('Undefined permission');
        }
        $this->permission = $permission;
    }

    public function add($permission)
    {
        if (!($this->permission & $permission)) {
            $this->permission |= $permission;
        }
    }

    public function is($permission)
    {
        return ($this->permission & $permission);
    }
}