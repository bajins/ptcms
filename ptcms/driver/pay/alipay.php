<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : alipay.php
 */
class Driver_Pay_Alipay extends PT_Base {

    protected $iurl='https://mapi.alipay.com/gateway.do';

    public function go($param) {

        $data = array(
            'service'        => 'create_direct_pay_by_user',
            '_input_charset' => 'utf-8',
            'partner'        => $this->config->get('pay_alipay_id'),
            'format'=>'xml',
            'v'=>'2.0',
            'sign_type'      => 'MD5',
            'notify_url'     => '',
            'return_url'     => '',
            'out_trade_no'   => '',
            'subject'        => '',
            'body'           => '',
            'payment_type'   => '1',
            'total_fee'      => '',
            'seller_id'      => $this->config->get('pay_alipay_id'),
            'seller_email'   => $this->config->get('pay_alipay_email'),
            'show_url'       => '',
        );
        $data=array_merge($data,$param);
        $data['sign']=$this->sign($data);
        return $this->iurl.'?' . http_build_query($data);
    }

    public function notify($param) {
        unset($param['m'],$param['c'],$param['a'],$param['s'],$param['f']);
        $sign=$this->sign($param);
        if ($sign==$param['sign']){
            if ($param['trade_status']=='TRADE_FINISHED' || $param['trade_status'] == 'TRADE_SUCCESS'){
                return array('status'=>1,'info'=>'success','money'=>$param['total_fee'],'time'=>strtotime(isset($param['gmt_payment']))?$param['gmt_payment']:$param['notify_time']);
            }else{
                return array('status'=>0,'info'=>'success');
            }
        }else{
            return array('status'=>0,'info'=>'fail');
        }
    }

    public function sign($data) {
        ksort($data);
        reset($data);
        $arg = '';
        foreach ($data as $key => $value) {
            if ($key != 'sign_type' && $key != 'sign' && $value!='') {
                $arg .= "$key=$value&";
            }
        }
        return md5(substr($arg, 0, -1) . $this->config->get('pay_alipay_key'));
    }
}