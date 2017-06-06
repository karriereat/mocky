<?php

namespace Karriere\Mocky;

class Path
{
    public static function join(...$paths)
    {
        $path = '';
        for ($i = 0; $i < count($paths); $i++) {
            $path = self::joinPaths($path, $paths[$i]);
        }

        return $path;
    }

    private static function joinPaths($path1, $path2)
    {
        if (empty($path1)) {
            return $path2;
        }

        if (empty($path2)) {
            return $path1;
        }

        if (substr($path1, -1) === DIRECTORY_SEPARATOR) {
            return $path1.$path2;
        }

        return $path1.DIRECTORY_SEPARATOR.$path2;
    }
}
