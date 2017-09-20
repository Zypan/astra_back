<?php
/**
 * Created by PhpStorm.
 * User: blackcat
 * Date: 20.09.17
 * Time: 15:09
 */

$source = '
{
	"users": {
		"admin": {
			"created": 1474356420,
			"type": 1,
			"cipher": "c4eed5a9793fcb7003fa41b99accd19d",
			"enable": true
		},
		"user": {
			"created": 1468320516,
			"type": 1,
			"cipher": "9003e8c7c8445830fe11187e61943757",
			"enable": true
		}
	},
	"make_stream": [
		{
			"type": "spts",
			"name": "STAR TV",
			"input": [
				"dvb://a02q#pnr=10800"
			],
			"id": "a02r",
			"enable": true
		},
		{
			"type": "spts",
			"name": "NTV",
			"input": [
				"dvb://a02q#pnr=10801"
			],
			"id": "a02s",
			"enable": true
		},
		{
			"type": "spts",
			"name": "NTV SPOR",
			"input": [
				"dvb://a02q#pnr=10802"
			],
			"id": "a02t",
			"enable": true
		},
		{
			"type": "spts",
			"name": "KRAL TV",
			"input": [
				"dvb://a02q#pnr=10817"
			],
			"id": "a02z",
			"enable": true
		},
		{
			"type": "spts",
			"name": "KRAL POP",
			"input": [
				"dvb://a02q#pnr=10809&no_sdt"
			],
			"id": "a02w",
			"enable": true
		},
		{
			"type": "spts",
			"name": "KRAL POP TV",
			"input": [
				"dvb://a02q#pnr=10816"
			],
			"id": "a02y",
			"enable": true
		},
		{
			"type": "spts",
			"name": "STAR TV HD",
			"input": [
				"dvb://a02q#pnr=10805"
			],
			"output": [
				"udp://eth1.35@239.239.0.9:1234"
			],
			"enable": true,
			"id": "a02u"
		},
		{
			"type": "spts",
			"name": "NTV HD",
			"input": [
				"dvb://a02q#pnr=10806"
			],
			"output": [
				"udp://eth1.35@239.239.0.7:1234"
			],
			"id": "a02v",
			"enable": true
		},
		{
			"type": "spts",
			"name": "NTV SPOR HD",
			"input": [
				"dvb://a02q#pnr=10815"
			],
			"output": [
				"udp://eth1.35@239.239.0.8:1234"
			],
			"enable": true,
			"id": "a02x"
		}
	],
	"dvb_tune": [
		{
			"type": "S2",
			"name": "TUR 42",
			"polarization": "H",
			"symbolrate": "27500",
			"frequency": "12015",
			"device": 0,
			"id": "a02q",
			"adapter": 9,
			"enable": true
		}
	],
	"gid": 466667,
	"settings": {
		"http_play_stream": true
	},
	"softcam": [
		{
			"type": "newcamd",
			"name": "test Sergei",
			"pass": "ip_kaskad",
			"host": "213.155.24.76",
			"port": "10005",
			"id": "a01w",
			"user": "bagira_3col_02"
		},
		{
			"caid": "4AE0",
			"type": "newcamd",
			"name": "Feniks TRIC",
			"pass": "fkekfr2ik39r23kklkj",
			"host": "82.202.210.198",
			"port": "9024",
			"id": "a01u",
			"user": "megahitHD"
		}
	],
	"clients": [
		{
			"pass": "wertg3tasretd",
			"name": "werg234gdsr",
			"enable": true
		}
	]
}

';

//print_r(json_decode($source)->softcam);
$data = json_decode($source);
$config = '';
foreach ($data->dvb_tune as $dvb){
//    a10 = dvb_tune({
//    mac = "00:22:AB:91:B5:99",
//    type = "S",
//    tp = "12092:H:28000"
//})

    $config .= $dvb->id.' = dvb_tune({'.PHP_EOL.
                '   type    = "'.$dvb->type.'",'.PHP_EOL.
                '   name    = "'.$dvb->name.'",'.PHP_EOL.
                '   tp      = "'.$dvb->frequency.':'.$dvb->polarization.':'.$dvb->symbolrate.'",'.PHP_EOL.
                '   adapter = "'.$dvb->adapter.'"'.PHP_EOL.
    '})'.PHP_EOL;


}

foreach ($data->softcam as $softcam){
//    cam_1 = newcamd({
//    name = "AXN White",
//    host = "127.0.0.1", port = 4011,
//    user = "iptv_10_0d97_", pass = "iptv",
//    key = "0102030405060708091011121314",
//    disable_emm = 1,
//})
    $config .= $softcam->id.' = newcamd({'.PHP_EOL.
        '   name    = "'.$softcam->name.'",'.PHP_EOL.
        '   host    = "'.$softcam->host.'",'.PHP_EOL.
        '   port    = "'.$softcam->port.'",'.PHP_EOL.
        '   user    = "'.$softcam->user.'",'.PHP_EOL.
        '   pass    = "'.$softcam->pass.'"'.PHP_EOL.
        ((isset($softcam->caid)) ? '   caid    = "'.$softcam->caid.'"'.PHP_EOL : '').
        '})'.PHP_EOL;


}

echo $config;