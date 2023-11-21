<?php

declare(strict_types=1);

namespace Bahge\AnalisysTools\Domain;

use Bahge\AnalisysTools\Constants\AppStrings;
use SplQueue;
use SplFixedArray;

class PerformanceTracker
{
    private SplQueue $events;
    public function __construct()
    {
        $this->events = new SplQueue();
        $this->events->enqueue([ 
            'time' =>  microtime(true),
            'msg' => AppStrings::$START_EVENTS,
            'processing_time' => 0]);
    }

    public static function create()
    {
        return new PerformanceTracker();
    }

    public function addMarkTracker(string $msg) 
    {
        $microtime = microtime(true);
        $this->events->enqueue([ 
            'time' => $microtime, 
            'msg' => $msg,
            'processing_time' => sprintf("%0.8f", $microtime - $this->events->top()['time'])
        ]);        
    }

    public function getTotals()
    {
        return sprintf(AppStrings::$PROCESSED_IN_SECONDS,  $this->events->top()['time'] - $this->events->bottom()['time']);
    }

    public function calc(int $precision = 2)
    {
        return number_format(($this->events->top()['time'] - $this->events->bottom()['time']), $precision, ',', '.');
    }

    public function getEventsTrackerToJson()
    {
        $arrayToJson = new SplFixedArray(count($this->events));

        for ($this->events->rewind(); $this->events->valid(); $this->events->next()) {
            $arrayToJson[$this->events->key()] = [ 
                'msg' => $this->events[$this->events->key()]['msg'], 
                'processing_time' => $this->events[$this->events->key()]['processing_time']
            ];
        }

        return json_encode($arrayToJson, JSON_UNESCAPED_UNICODE);
    }

    public function getEventsTracker()
    {
        return $this->events->serialize();
    }
}