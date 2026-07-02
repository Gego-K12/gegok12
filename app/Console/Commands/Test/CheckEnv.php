<?php
namespace App\Console\Commands\Test;
use Illuminate\Console\Command;
class CheckEnv extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'gego:checkenv';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Check Env';
   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct()
   {
       parent::__construct();
   }
   /**
    * Execute the console command.
    *
    * @return mixed
    */
   public function handle()
   {
      $env_var = $this->ask('Enter the Env Variable');
      $this->line(var_export(env($env_var), true));
   }
}