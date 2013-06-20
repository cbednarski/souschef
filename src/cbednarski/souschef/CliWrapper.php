<?php

namespace cbednarski\souschef;


class CliWrapper
{
    public static function execute($command)
    {
        return shell_exec($command);
    }
}