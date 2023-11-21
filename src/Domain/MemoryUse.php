<?php

declare(strict_types=1);

namespace Bahge\AnalisysTools\Domain;

class MemoryUse
{
    private float $startMemory;
    private float $finishMemory;
    public function __construct()
    {
        $this->startMemory = memory_get_usage();
    }

    public static function create()
    {
        return new MemoryUse();
    }

    public function calc()
    {
        $this->finishMemory = memory_get_usage();
        return $this->finishMemory - $this->startMemory;
    }

    public function calcKb(int $precision = 2)
    {
        return number_format((($this->calc()) / 1024 ), $precision, ',', '.') . " Kb";
    }

    public function calcMb(int $precision = 2)
    {
        return number_format(( ($this->calc()) / (1024 ** 2) ), $precision, ',', '.') . " Mb";
    }

}