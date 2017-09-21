#!/usr/bin/php

<?php
/**
 * Created by PhpStorm.
 * User: blackcat
 * Date: 20.09.17
 * Time: 15:09
 */


foreach (glob("*.conf") as $filename) {

    $data = json_decode(file_get_contents($filename));


    $config = '';
    if (isset($data->dvb_tune)) {
        foreach ($data->dvb_tune as $dvb) {
//    a10 = dvb_tune({
//    mac = "00:22:AB:91:B5:99",
//    type = "S",
//    tp = "12092:H:28000"
//})

            $config .= $dvb->id . ' = dvb_tune({' . PHP_EOL .
                '   type    = "' . $dvb->type . '",' . PHP_EOL .
                '   name    = "' . $dvb->name . '",' . PHP_EOL .
                '   tp      = "' . $dvb->frequency . ':' . $dvb->polarization . ':' . $dvb->symbolrate . '",' . PHP_EOL .
                '   adapter = "' . $dvb->adapter . '"' . PHP_EOL .
                (isset($dvb->diseqc) ?
                    '   diseqc  = "' . $dvb->adapter . '"' . PHP_EOL : '') .
                '})' . PHP_EOL;


        }

        $config .= PHP_EOL;
    }

    if (isset($data->softcam)) {
        foreach ($data->softcam as $softcam) {
//    cam_1 = newcamd({
//    name = "AXN White",
//    host = "127.0.0.1", port = 4011,
//    user = "iptv_10_0d97_", pass = "iptv",
//    key = "0102030405060708091011121314",
//    disable_emm = 1,
//})
            $config .= $softcam->id . ' = newcamd({' . PHP_EOL .
                '   name    = "' . $softcam->name . '",' . PHP_EOL .
                '   host    = "' . $softcam->host . '",' . PHP_EOL .
                '   port    = "' . $softcam->port . '",' . PHP_EOL .
                '   user    = "' . $softcam->user . '",' . PHP_EOL .
                '   pass    = "' . $softcam->pass . '"' . PHP_EOL .
                ((isset($softcam->caid)) ? '   caid    = "' . $softcam->caid . '"' . PHP_EOL : '') .
                '})' . PHP_EOL;


        }

        $config .= PHP_EOL;
    }

    if (isset($data->make_stream)) {
        foreach ($data->make_stream as $stream) {

//    make_channel({
//    name = "AXN White",
//    timeout = 4,
//    input = { "dvb://a10#pnr=11002&filter~=5102,5202&cam=cam_1" },
//    output = { "http://10.5.5.1:42860/10_1#keep_active" },
//enable = true
//})

            $config .= 'make_channel({' . PHP_EOL .
                '   name    = "' . $stream->name . '",' . PHP_EOL .
                '   id      = "' . $stream->id . '",' . PHP_EOL .
                '   enable  = ' . (($stream->enable) ? 'true' : 'false') . ',' . PHP_EOL .
                '   input   = { "' . implode('","', $stream->input) . '"},' . PHP_EOL .
                ((isset($stream->output)) ?
                    '   output  = { "' . implode('","', $stream->output) . '"},' . PHP_EOL : '') .
                '})' . PHP_EOL;

        }
    }


    $new_name = pathinfo($filename, PATHINFO_FILENAME);

    file_put_contents('v4/' . $new_name.'_v4.conf', $config);

//Systemd Config
$sysd = '[Unit]
Description=Astra '.$new_name.'
After=network-online.target

[Service]
TimeoutStartSec=10
LimitNOFILE=65536
ExecStart=/usr/local/bin/astra-free -c /etc/astra/v4/'.$new_name.'_v4.conf --log /var/log/'.$new_name.'_v4.log
ExecStop=/bin/kill $MAINPID
Restart=on-failure

[Install]
WantedBy=multi-user.target';

    file_put_contents('/lib/systemd/system/'.$new_name.'.service',$sysd);

}