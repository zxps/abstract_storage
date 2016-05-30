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
class Main {

    public static function main()
    {

        // Source storage
        $local = new \Services\MyLocalDatabaseStorage();

        // Destination storage
        $remote = new \Services\RemoteCloudStorage();

        $transmitter = new \Storage\Transmitter\Transmitter($local, $remote);
        $transmitter->transmit();


        // Source storage
        $local = new \Services\MyLocalDatabaseStorage();
        $remote1 = new \Services\RemoteCloudStorage();
        $remote2 = new \Services\RemoteSocketStorage();

        $transmitter = new \Storage\Transmitter\MultiTransmitter($local, [$remote1, $remote2]);
        $transmitter->transmit();
    }
}