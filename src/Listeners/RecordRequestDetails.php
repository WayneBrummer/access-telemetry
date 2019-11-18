<?php

namespace Pace\AccessTelemetry\Listeners;

use Illuminate\Support\Carbon;
use Pace\AccessTelemetry\Jobs\RecordAccessLog;

class RecordRequestDetails
{
    protected $logVariables = [
        'origin'             => 'HTTP_ORIGIN',
        'real_ip'            => 'HTTP_X_REAL_IP',
        'user_agent'         => 'HTTP_USER_AGENT',
        'server_name'        => 'SERVER_NAME',
        'server_address'     => 'SERVER_ADDR',
        'server_port'        => 'SERVER_PORT',
        'remote_port'        => 'REMOTE_PORT',
        'remote_address'     => 'REMOTE_ADDR',
        'forwarded_for'      => 'HTTP_X_FORWARDED_FOR',
        'forwarded_host'     => 'HTTP_X_FORWARDED_HOST',
        'forwarded_port'     => 'HTTP_X_FORWARDED_PORT',
        'forwarded_protocol' => 'HTTP_X_FORWARDED_PROTO',
        'forwarded_server'   => 'HTTP_X_FORWARDED_SERVER',
    ];

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param \Pace\AccessTelemetry\Events\RequestLoginEvent $event
     */
    public function handle(RequestLoginEvent $event)
    {
        $credentials = $event->credentials;
        $data        = \array_merge([
            'username'  => $credentials['username'],
            'client_id' => (int) $credentials['client_id'],
        ], $this->data($event->server));

        dispatch(new RecordAccessLog($data));
    }

    /**
     * Map data for Model.
     */
    public function data($server)
    {
        $data = [
            'request_time' => (string) Carbon::createFromTimestamp($server['REQUEST_TIME']),
        ];

        foreach ($this->logVariables as $key => $sKey) {
            $data[$key] = null;

            if (\array_key_exists($sKey, $server) && $server[$sKey]) {
                $data[$key] = $server[$sKey];
            }
        }

        return $data;
    }
}
