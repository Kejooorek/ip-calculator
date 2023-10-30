<?php

class KalkIP {
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
        foreach($_ip as $octet)
        {
            if($ip!=0)
                $ip <<= 8;
            $ip |= ($octet*1);
        }
        return $ip;
    }
    public function converterMask($mask){
        $maska = 0;
        for($i=32;$i>0;$i--)
        {
            if($i<32)$maska <<=1;
            if($i>(32-$mask))
                $maska |= 1;

        }
        return $maska;
    }

    public function decoderIP($ip)
    {
        $x = explode("/", $ip);
        $this->ip = $this->converterIP($x[0]); 
        if(strpos($x[1],'.')!==false)
            $this->mask = $this->converterIP($x[1]);
        else
            $this->mask = $this->converterMask(($x[1]*1));
    }

    public function toOcted($address){
        $tab['address'] = $address;
        $tab['o1'] = ($address & (0xff000000))>>24;
        $tab['o2'] = ($address & (0x00ff0000))>>16;
        $tab['o3'] = ($address & (0x0000ff00))>>8;
        $tab['o4'] = ($address & (0x000000ff));
        return $tab;
    }

    public function __construct($ip){
        $this->decoderIP($ip);
        $this->network = $this->ip & $this->mask;
        $this->multicast = $this->network | ((~$this->mask) & 0xffffffff);
        $this->minip = $this->network + 1;
        $this->maxip = $this->multicast - 1;
        $this->maxhost = $this->maxip - $this->minip + 
        1;

            // echo sprintf("%032b\r\n%032b\r\n%032b\r\n%032b\r\n%032b\r\n%032b\r\n%d", 
        //             $this->ip, $this->mask, $this->network,$this->multicast, $this->minip, $this->maxip, $this->maxhost);

    }

    public function toJSON()
    {
        $tab['ip'] = $this->toOcted($this->ip); 
        $tab['mask'] = $this->toOcted($this->mask);
        $tab['minip'] = $this->toOcted($this->minip);
        $tab['maxip'] = $this->toOcted($this->maxip);
        $tab['network'] = $this->toOcted($this->network);
        $tab['multicast'] = $this->toOcted($this->multicast);
        $tab['maxhost'] = $this->maxhost;
        echo json_encode($tab);
    }

}

$kalk = new KalkIP($_GET['ip']); // a.a.a.a/b.b.b.b
$kalk->toJSON();