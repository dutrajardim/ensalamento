<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Symfony\Component\Process\Process;
use Collective\Remote\RemoteFacade as SSH;
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
        
        $host = env('SSH_HOST', '');
        $user = env('SSH_USERNAME', '');
        $pass = env('SSH_PASSWORD', '');

        $gurobi_command = env('CALL_GUROBI_COMMAND', '');
        
        $command = $gurobi_command.' -h ' . $this->code;

        if (!$host || !$user || !$pass) throw new Exception("Error Processing Request", 1);
        
        $connection = ssh2_connect($host, 22);
        ssh2_auth_password($connection, $user, $pass);
        
        $stream = ssh2_exec($connection, '/bin/sh -c "'.$command.'"');
        
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        
        stream_set_blocking($stream, true);
        stream_set_blocking($errorStream, true);

        $errorStreamContent = stream_get_contents($errorStream);
        $success = stream_get_contents($stream);
        
        if ($success) echo $success;
        if ($errorStreamContent) echo $errorStreamContent;

        fclose($stream);
        fclose($errorStreamContent);
    }
}
