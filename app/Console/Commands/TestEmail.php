<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar un correo de prueba para verificar la configuración SMTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Intentando enviar correo de prueba a: {$email}");

        try {
            Mail::raw('Este es un correo de prueba desde UniversoDev', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Correo de Prueba - UniversoDev');
            });

            $this->info("✓ Correo enviado exitosamente a {$email}");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("✗ Error al enviar el correo:");
            $this->error($e->getMessage());
            Log::error('Error en test:email', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }
}
