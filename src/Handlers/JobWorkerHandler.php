<?php

namespace Revobot\Handlers;

use Revobot\RequestHandlerInterface;

class JobWorkerHandler implements RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri)
    {
        $kphp_job_request = kphp_job_worker_fetch_request();
        if (!$kphp_job_request) {
            warning("Couldn't fetch a job worker request");
            return;
        }
        if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerSimple) {
            // simple jobs: they start, finish, and return the result
            $kphp_job_request->beforeHandle();
            $response = $kphp_job_request->handleRequest();
            if ($response === null) {
                warning("Job request handler returned null for " . get_class($kphp_job_request));
                return;
            }
            kphp_job_worker_store_response($response);
        } else if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerManualRespond) {
            // more complicated jobs: they start, send a result in the middle (here get get it) — and continue working
            $kphp_job_request->beforeHandle();
            $kphp_job_request->handleRequest();
            if (!$kphp_job_request->wasResponded()) {
                warning("Job request handler didn't call respondAndContinueExecution() manually " . get_class($kphp_job_request));
            }
        } else if ($kphp_job_request instanceof \Revobot\JobWorkers\JobWorkerNoReply) {
            // background jobs: they start and never send any result, just continue in the background and finish somewhen
            $kphp_job_request->beforeHandle();
            $kphp_job_request->handleRequest();
        } else {
            warning("Got unexpected job request class: " . get_class($kphp_job_request));
        }
    }
}
