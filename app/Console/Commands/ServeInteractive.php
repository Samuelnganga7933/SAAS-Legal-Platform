<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeInteractive extends Command
{
    protected $signature = 'serve:interactive';
    protected $description = 'Start or stop the Laravel development server interactively';

    public function handle()
    {
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║  Le Nium Advisors - Server Control     ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        $choice = $this->choice(
            'What would you like to do?',
            ['Start Server', 'Stop Server', 'Restart Server', 'Status Check'],
            0
        );

        match($choice) {
            'Start Server' => $this->startServer(),
            'Stop Server' => $this->stopServer(),
            'Restart Server' => $this->restartServer(),
            'Status Check' => $this->checkStatus(),
        };
    }

    private function startServer()
    {
        $this->info('🚀 Starting the development server...');
        $this->newLine();

        $port = $this->ask('Enter the port (default: 8000)', '8000');
        $host = $this->ask('Enter the host (default: 127.0.0.1)', '127.0.0.1');

        $process = new Process([
            'php',
            base_path('artisan'),
            'serve',
            '--host=' . $host,
            '--port=' . $port,
        ]);

        $this->info("Server starting on http://{$host}:{$port}");
        $this->info('Press Ctrl+C to stop the server');
        $this->newLine();

        try {
            $process->setTty(true);
            $process->run();
        } catch (\Exception $e) {
            $this->error('Error starting server: ' . $e->getMessage());
        }
    }

    private function stopServer()
    {
        $this->info('🛑 Stopping the server...');
        
        // Attempt to kill the Laravel serve process
        $systemCommand = PHP_OS_FAMILY === 'Windows' 
            ? 'taskkill /F /IM php.exe 2>nul'
            : 'pkill -f "artisan serve"';

        $process = new Process(['sh', '-c', $systemCommand]);
        
        if (PHP_OS_FAMILY === 'Windows') {
            $process = new Process(['cmd', '/c', $systemCommand]);
        }

        $process->run();
        $this->info('✅ Server stopped successfully!');
    }

    private function restartServer()
    {
        $this->info('🔄 Restarting the server...');
        $this->stopServer();
        sleep(2);
        $this->startServer();
    }

    private function checkStatus()
    {
        $port = $this->ask('Check which port? (default: 8000)', '8000');
        
        $process = new Process(['curl', '-s', "http://127.0.0.1:{$port}/"]);
        $process->setTimeout(3);

        try {
            $process->run();
            if ($process->isSuccessful()) {
                $this->info("✅ Server is running on port {$port}");
            } else {
                $this->warn("⚠️  Server is not responding on port {$port}");
            }
        } catch (\Exception $e) {
            $this->warn("⚠️  Could not connect to server on port {$port}");
        }
    }
}
