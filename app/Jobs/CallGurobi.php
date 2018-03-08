<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Symfony\Component\Process\Process;
use Collective\Remote\RemoteFacade as SSH;
use Symfony\Component\Process\Exception\ProcessFailedException;

use App\Ensalamentos;

class CallGurobi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code;
    protected $year;
    protected $semester;

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
    public function __construct($code, $year, $semester)
    {
        $this->code = $code;
        $this->year = $year;
        $this->semester = $semester;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ensalamento = '';
        try {
            $ensalamento = Ensalamentos::find($this->code);
            $ensalamento->status = 'T';
            $ensalamento->save();

            $host = env('SSH_HOST', '');
            $user = env('SSH_USERNAME', '');
            $pass = env('SSH_PASSWORD', '');

            $gurobi_command = env('CALL_GUROBI_COMMAND', '');
            
            $command = $gurobi_command.' -e ' . $this->code . ' -a ' . $this->year . ' -s ' . $this->semester;

            if (!$host || !$user || !$pass) throw new Exception("Error Processing Request", 1);
            
            $connection = ssh2_connect($host, 22);
            ssh2_auth_password($connection, $user, $pass);
            
            echo "Enviando comando: ".'/bin/bash -c "'.$command.'"'.PHP_EOL;
            $stream = ssh2_exec($connection, '/bin/bash -c "'.$command.'"');
            
            stream_set_blocking($stream, true);
            $success = stream_get_contents($stream);
            
            if ($success) echo "Success: ".$success.PHP_EOL;

            fclose($stream);
    
            $ensalamento->status = 'P';
            $ensalamento->save();
        }
        catch(Exception $e) {
            if ($ensalamento) {
                $ensalamento->status = 'E';
                $ensalamento->save();
            }
        }
    }

    public function failed(Exception $exception)
    {
        $ensalamento = Ensalamentos::find($this->code);
        $ensalamento->status = 'E';
        $ensalamento->save();
    }
}
