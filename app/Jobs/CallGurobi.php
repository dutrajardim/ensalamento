<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CallGurobi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code;
     /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $process = new Process('ls -lha');
        // SSH::run([
        //     'mkdir -p {/home/rafael/teste_ssh}'
        // ], function($line) {

        //     echo $line.PHP_EOL;
        // });
        $process = new Process('cd /home/rafael/Projetos/CppTestes/dist/Debug/GNU-Linux && LD_LIBRARY_PATH=/opt/gurobi751/linux64/lib && ls && ./cpptestes -h ' . $this->code);
        $process->start();
        
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo "\nRead from stdout: ".$data;
            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        }
    }
}
