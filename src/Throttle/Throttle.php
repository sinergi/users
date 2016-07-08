<?php

namespace Sinergi\Users\Throttle;

use DateTime;

class Throttle
{
    private static function file(): string
    {
        return sys_get_temp_dir() . '/sinergi_users_throttle.json';
    }

    private static function pool(): array
    {
        if (!file_exists(self::file())) {
            touch(self::file());
        }
        $content = json_decode(file_get_contents(self::file()), true);
        return is_array($content) ? $content : [];
    }

    private static function save(array $pool)
    {
        file_put_contents(self::file(), json_encode($pool));
    }

    public static function throttle(string $ip)
    {
        if ($ip) {
            $pool = self::pool();
            if (isset($pool[$ip])) {
                $lastAttempt = ((new Datetime())->getTimestamp() - (new DateTime($pool[$ip][1]))->getTimestamp());
                if ($lastAttempt > 300) {
                    $pool[$ip][0] = 1;
                } else {
                    $pool[$ip][0] += 1;
                }
            } else {
                $pool[$ip] = [];
                $pool[$ip][0] = 1;
            }
            $pool[$ip][1] = (new DateTime())->format('Y-m-d H:i:s');

            self::save($pool);

            if ($pool[$ip][0] > 10) {
                sleep($pool[$ip][0] - 10);
            }
        }
    }
}
