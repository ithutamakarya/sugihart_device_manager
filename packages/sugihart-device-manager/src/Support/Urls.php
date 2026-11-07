<?php

namespace Sugihart\DeviceManager\Support;

class Urls
{
    public static function page(string $path = ''): string
    {
        return url(trim(config('sugihart-device-manager.route_prefix'), '/').'/'.ltrim($path, '/'));
    }

    public static function asset(string $path = ''): string
    {
        return asset(trim(config('sugihart-device-manager.asset_prefix'), '/').'/'.ltrim($path, '/'));
    }
}
