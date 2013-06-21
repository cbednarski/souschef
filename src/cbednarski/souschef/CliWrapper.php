<?php

namespace cbednarski\souschef;


class CliWrapper
{
    public static function execute($command)
    {
        return passthru($command);
    }
}