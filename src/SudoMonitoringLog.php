<?php

namespace Sudo\MonitoringLog;

use GuzzleHttp\Client;
use Exception;
use Log;

class SudoMonitoringLog
{

    public const JOB_STATUS_FAIL = 0;
    public const JOB_STATUS_SUCCESS = 1;

    public static function jobStatusDropdown():array
    {
        return [
            self::JOB_STATUS_SUCCESS => 'Thành công',
            self::JOB_STATUS_FAIL    => 'Thất bại',
        ];
    }

    /**
     * Write success log monitoring
     * @param integer $job_item_id Job item id
     * @param string  $message Message in job
     */
    public static function success($job_item_id, $message){
        self::writeLog($job_item_id, $message, self::JOB_STATUS_SUCCESS);
    }

    /**
     * Write error log monitoring
     * @param integer $job_item_id Job item id
     * @param string  $message Message in job
     */
    public static function error($job_item_id, $message){
        self::writeLog($job_item_id, $message, self::JOB_STATUS_FAIL);
    }


    /**
     * Write log API
     * @param integer $job_item_id Job item id
     * @param string  $message Message in job
     * @param integer $status  Job status
     */
    private static function writeLog($job_item_id, $message, $status){
        try{
            $host = config('SudoMonitoringLog.host');

            $token = config('SudoMonitoringLog.token');
            $url_request = "$host/api/v1/write-log";

            $client = new Client(['verify' => false, 'timeout'  => 10]);
            $respon = $client->request('POST', $url_request, [
                'form_params' => [
                    'job_status'   => $status,
                    'job_item_id'  => $job_item_id,
                    'message'      => $message,
                    'token'        => $token,
                    'website'      => config('app.url')
                ]
            ]);
            if ($respon->getStatusCode() == 200) {

                $res = json_decode($respon->getBody());

                if(!($res->success ?? 0)){
                    throw new Exception($res->message);
                }
            }
        }catch(Exception $e){
            Log::error("Error write log monitor:");
            Log::error($e->getMessage());
        }
    }


}