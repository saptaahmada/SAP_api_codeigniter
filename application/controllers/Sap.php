<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sap extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}

    public function index()
    {
		ini_set("soap.wsdl_cache_enabled", "0");
        $url = "YOUR URL SAP";
        $user     = 'YOUR USERNAME';
        $password = 'YOUR PASSWORD';

        $post_string = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
						    <Body>
						        <ZFM_MASTER_VENDOR xmlns="urn:sap-com:document:sap:rfc:functions">
						            <LIFNR xmlns=""></LIFNR>
						            <ZMMS001 xmlns=""></ZMMS001>
						        </ZFM_MASTER_VENDOR>
						    </Body>
						</Envelope>';

        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL,            $url);
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT,        60);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST,           true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $post_string);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8', 'Content-Length: ' . strlen($post_string)));
        curl_setopt($soap_do, CURLOPT_USERPWD, $user . ":" . $password);

        $response = curl_exec($soap_do);
        $err = curl_error($soap_do);

        $response = strtr($response, [
            '</SOAP:' => '</', '<SOAP:' => '<',
            '<ns0:' => '<', '</ns0:' => '</'
        ]);

        $output = json_decode(json_encode(simplexml_load_string($response)->Body->{'ZFM_MASTER_VENDOR.Response'}->ZMMS001));

        $result = [];
        if (isset($output->item)) {
            foreach ($output->item as $key => $value) {
                $result[] = [
                	'LIFNR' => !is_string($value->LIFNR) ? null : $value->LIFNR,
					'LAND1' => !is_string($value->LAND1) ? null : $value->LAND1,
					'NAME1' => !is_string($value->NAME1) ? null : $value->NAME1,
					'ORT01' => !is_string($value->ORT01) ? null : $value->ORT01,
					'SORTL' => !is_string($value->SORTL) ? null : $value->SORTL,
					'STRAS' => !is_string($value->STRAS) ? null : $value->STRAS,
					'MCOD3' => !is_string($value->MCOD3) ? null : $value->MCOD3,
					'STCD1' => !is_string($value->STCD1) ? null : $value->STCD1,
					'SIUP_NO' => !is_string($value->SIUP_NO) ? null : $value->SIUP_NO,
					'SIUP_DAT' => !is_string($value->SIUP_DAT) ? null : $value->SIUP_DAT,
					'PKP_NO' => !is_string($value->PKP_NO) ? null : $value->PKP_NO,
					'PKP_DAT' => !is_string($value->PKP_DAT) ? null : $value->PKP_DAT,
					'AKONT' => !is_string($value->AKONT) ? null : $value->AKONT,
					'ZTERM' => !is_string($value->ZTERM) ? null : $value->ZTERM,
					'BANKL' => !is_string($value->BANKL) ? null : $value->BANKL,
					'BANKN' => !is_string($value->BANKN) ? null : $value->BANKN,
					'KOINH' => !is_string($value->KOINH) ? null : $value->KOINH,
					'WITHT' => !is_string($value->WITHT) ? null : $value->WITHT,
					'KET_TAX' => !is_string($value->KET_TAX) ? null : $value->KET_TAX,
					'WT_WITHCD' => !is_string($value->WT_WITHCD) ? null : $value->WT_WITHCD,
					'EKGRP' => !is_string($value->EKGRP) ? null : $value->EKGRP
                ];
            }
        }

		echo json_encode($result);
    }

}
