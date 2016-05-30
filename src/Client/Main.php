<?php

/**
 * Class Main
 *
 * Необходимо реализовать абстрактный уровень библиотеки
 * по экспорту из хранилища А в хранилище Б.
 * Хранилище А и Б могут быть любыми, файл, БД, облако и т.д.
 * Разрешается использовать только PHP 5.6 и выше
 *
 */

namespace Client;

use Services\AnyDatabaseStorage;
use Services\AwesomeCloudStorage;
use Services\AwesomeSocketStorage;
use Storage\Permissions;
use Storage\Transmitter\MultiTransmitter;
use Storage\Transmitter\Transmitter;

class Main {

    public static function main()
    {

        // Source storage
        $from = new AnyDatabaseStorage(new Permissions(Permissions::READ));

        // Destination storage
        $dest = new AwesomeCloudStorage(new Permissions(Permissions::WRITE));

        $transmitter = new Transmitter($from, $dest);
        $transmitter->transmit();


        // Source storage
        $from = new AnyDatabaseStorage(new Permissions(Permissions::READ));

        // First remote storage
        $dest1 = new AwesomeCloudStorage(new Permissions(Permissions::WRITE));
        // Second remote storage
        $dest2 = new AwesomeSocketStorage(new Permissions(Permissions::WRITE));

        $transmitter = new \Storage\Transmitter\MultiTransmitter($from, [$dest1, $dest2]);
        $transmitter->transmit();


        // Change
        $transmitter = new Transmitter(
            new AwesomeCloudStorage(new Permissions(Permissions::READ)),
            new AnyDatabaseStorage(new Permissions(Permissions::WRITE))
        );
        $transmitter->transmit();

    }
}