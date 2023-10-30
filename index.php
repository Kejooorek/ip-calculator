<?php

class KalkIP
{
    private $ip = 0;
    private $mask = 0;
    private $network = 0;
    private $minip = 0;
    private $maxip = 0;
    private $multicast = 0;
    private $maxhost = 0;
    public function converterIP($ip)
    {
        $_ip = explode(".", $ip);
        $ip = 0;
        foreach ($_ip as $octet) {
            if ($ip != 0)
                $ip <<= 8;
            $ip |= ($octet);
        }
        return $ip;
    }
    public function converterMask($mask)
    {
        $maska = 0;
        for ($i = 32; $i > 0; $i--) {
            if ($i < 32) $maska <<= 1;
            if ($i > (32 - $mask))
                $maska |= 1;
        }
        return $maska;
    }

    public function decoderIP($ip)
    {
        $x = explode("/", $ip);
        $this->ip = $this->converterIP($x[0]);
        if (strpos($x[1], '.') !== false)
            $this->mask = $this->converterIP($x[1]);
        else
            $this->mask = $this->converterMask(($x[1]));
    }

    public function __construct($ip)
    {
        $this->decoderIP($ip);
        $this->network = $this->ip & $this->mask;
        $this->minip = $this->network + 1;
        $this->multicast = $this->network | ((~$this->mask) & 0xffffffff);
        $this->maxip = $this->multicast - 1;
        $this->maxhost = $this->maxip - $this->minip;
        echo sprintf(
            "%032b\r\n%032b\r\n%032b\n%032b\r\n%032b\r\n%032b",
            $this->ip,
            $this->mask,
            $this->network,
            $this->multicast,
            $this->minip,
            $this->maxip,
            $this->maxhost
        );
    }
}

$kalk = new KalkIP("10.10.10.10/24"); // a.a.a.a/b.b.b.b