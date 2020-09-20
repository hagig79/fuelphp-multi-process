<?php

namespace Fuel\Tasks;

class Multi_Process_Child
{
    public function run($user)
    {
        echo "child {$user} start\n";
        sleep(rand(3, 10));
        echo "child {$user} end\n";
    }
}
