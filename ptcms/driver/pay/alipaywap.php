<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : alipay.php
 */
class Driver_Pay_alipaywap extends Driver_Pay_Alipay {

    protected $iurl = 'http://wappaygw.alipay.com/service/rest.htm';

    public function go($param) {
        $req_data = '<direct_trade_create_req>';
        $req_data .= '<notify_url>' . $param['notify_url'] . '</notify_url>';
        $req_data .= '<call_back_url>' . $param['return_url'] . '</call_back_url>';
        $req_data .= '<seller_account_name>' . $this->config->get('pay_alipay_email') . '</seller_account_name>';
        $req_data .= '<out_trade_no>' . $param['out_trade_no'] . '</out_trade_no>';
        $req_data .= '<subject>' . $param['subject'] . '</subject>';
        $req_data .= '<total_fee>' . $param['total_fee'] . '</total_fee>';
        $req_data .= '</direct_trade_create_req>';

        $data         = array(
            'service'        => 'alipay.wap.trade.create.direct',
            '_input_charset' => 'utf-8',
            'partner'        => $this->config->get('pay_alipay_id'),
            'sec_id'         => 'MD5',
            'req_id'         => date('Ymdhis'),
            'req_data'       => $req_data,
            'v'              => '2.0',
            'format'=>'xml',
        );
        $data['sign'] = $this->sign($data);
        $res          = urldecode(http::post($this->iurl, $data));
        parse_str($res,$param);
        if(!empty($param['res_data'])){
            $data=$this->load('xml')->decode($param['res_data']);
            //业务详细
            $req_data = '<auth_and_execute_req><request_token>' . $data['request_token'] . '</request_token></auth_and_execute_req>';
            $data         = array(
                'service'        => 'alipay.wap.auth.authAndExecute',
                '_input_charset' => 'utf-8',
                'partner'        => $this->config->get('pay_alipay_id'),
                'sec_id'         => 'MD5',
                'req_id'         => date('Ymdhis'),
                'req_data'       => $req_data,
                'v'              => '2.0',
                'format'=>'xml',
            );
            $data['sign'] = $this->sign($data);
            return $this->iurl . '?' . http_build_query($data);
        }
        return false;
    }
}