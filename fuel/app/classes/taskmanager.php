<?php

class TaskManager
{
    private $all_args;
    private $parallels;
    private $command;

    /**
     * @param array $all_args
     * @param int $parallels
     * @param Closure $command
     */
    public function __construct(array $all_args, int $parallels, Closure $command)
    {
        $this->all_args = $all_args;
        $this->parallels = $parallels;
        $this->command = $command;
    }

    /**
     * 並列処理を実行する
     */
    public function start()
    {
        $running_children = [];

        while (!empty($this->all_args) || !empty($running_children)) {
            // cleanup child process
            foreach ($running_children as $key => $running_child) {
                $status = proc_get_status($running_child);
                if (!$status['running']) {
                    proc_close($running_child);
                    unset($running_children[$key]);
                }
            }
            $running_children = array_values($running_children);

            // start child process
            while (!empty($this->all_args) && count($running_children) < $this->parallels) {
                $args = array_shift($this->all_args);
                $pipes = [];
                $env = \Fuel::$env;
                $command = "FUEL_ENV={$env} php oil r " . implode(' ', ($this->command)($args));
                echo $command . "\n";
                $running_children[] = proc_open(
                    $command,
                    [],
                    $pipes,
                    realpath(APPPATH . '/../../')
                );
            }
            sleep(1);
        }
    }
}
