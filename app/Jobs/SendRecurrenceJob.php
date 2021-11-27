<?php

namespace App\Jobs;

use App\Models\Recurrence;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendRecurrenceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const VERSION_RECURRENTE = 'v1';

    protected $recurrence;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Recurrence $recurrence)
    {
        //
        $this->recurrence = $recurrence;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $objRecurrence = $this->recurrence;
        $objAccount = $objRecurrence->account;
        $enviroment = $objAccount->enviroment;

        $client = new \GuzzleHttp\Client(['cookies' => true]);

        $data = [
            'multipart' => [
                [
                    'name' => 'ARQUIVO',
                    'filename' => $objRecurrence->filename,
                    'Mime-Type'  => 'text/plain',
                    'contents' => Storage::get($objRecurrence->filename),
                ]
            ]
        ];

        switch ($enviroment) {
            case 'local':
                $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray([
                    'PHPSESSID' => $this->getSessionPJBank(),
                ], 'localhost');
                $data['cookies'] = $cookieJar;
                break;
            case 'estagio':
                $data['headers'] = [
                    'app_token' => 'NXzUYvZwNyRI',
                    'access_token' => 'XcYma7HD8Rxc'
                ];
                break;
        }

        $urlRetorno = config('pjbank')[$enviroment]['retorno'];
        $response = $client->post($urlRetorno, $data);

        $json = json_decode((string) $response->getBody(), true);
        Log::info((string) $response->getBody());
        $json['data'][0]['status'] ?? $response->getStatusCode();
        $response = $client->get(config('pjbank')[$enviroment]['liquidacao']);
    }

    private function getSessionPJBank()
    {
        return Cache::remember('session_pjbank_' . self::VERSION_RECURRENTE, 60 * 60 * 1, function(){
            $client = new \GuzzleHttp\Client();
            $response = $client->post('http://localhost:3059/financeiro/atual/auth/post', [
                'json' => [
                    'username' => 'dummy@empresa.com',
                    'password' => '123456',
                    'filename' => 'subadquirentete-001'
                ]
            ]);

            switch ($response->getStatusCode()) {
                case 200:
                    $session = json_decode((string) $response->getBody())->session;
                    break;
                default:
                    throw new Exception("ERRO NO LOGIN");
            }

            $urlBase = 'http://localhost:3059';
            $response = $client->get($urlBase, $paramsQuery = [
                'query' => ['licenca' => 'subadquirentete-001', 'filename' => 'subadquirentete-001']
            ]);

            $responseArray = json_decode($response->getBody(), true);

            // caso de errado, retornará um JSON válido ou uma string vazia
            if (!$response->getBody() || (json_last_error() === JSON_ERROR_NONE && $responseArray['status'])) {
                print_r($response);
                throw new \Exception("ERRO AO TENTAR ACESSAR O APP (1)");
            }

            $response = $client->get($urlBase . '/clients/financeiro/apps/index/id/3/id_appstore/114', [
                'cookies' => \GuzzleHttp\Cookie\CookieJar::fromArray([
                    'PHPSESSID' => $session,
                ], 'localhost'),
            ]);
            $responseArray = json_decode($response->getBody(), true);

            if (!$response->getBody() || (json_last_error() === JSON_ERROR_NONE && $responseArray['status'])) {
                print_r($response);
                throw new \Exception("ERRO AO TENTAR ACESSAR O APP (2)");
            }
            return $session;
        });
    }
}
