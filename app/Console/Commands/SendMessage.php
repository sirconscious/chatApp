<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:m {--message=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $message = $this->option("message"); 
         $user = User::find(1);
            event(new \App\Events\testEvenet($message , $user ));
    }
}
