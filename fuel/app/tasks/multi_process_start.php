<?php

namespace Fuel\Tasks;

class Multi_Process_Start
{
    /**
     * 10人のユーザーを3人ずつ処理する
     */
    public function run(int $parallels = 3)
    {
        $users = range(0, 9);

        $manager = new \TaskManager(
            $users,
            $parallels,
            function ($user) {
                return ["multi_process_child", $user];
            }
        );

        echo "Multi_Process_Start start!\n";

        $manager->start();

        echo "Multi_Process_Start complete!\n";
    }
}
