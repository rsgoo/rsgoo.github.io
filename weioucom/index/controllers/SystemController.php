<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-9 下午3:50:41
 * @Version V1.0
 */
class SystemController extends Weiou_Controller_Web {

    /**
     * ajax download 下载次数+1
     */
    public function statDownloadAction() {
        $this->view->setNoRender(true);
        $platform = $this->getParam("platform");
        $type = $this->getParam("type", "web");
        if ($platform) {
            $download = new Weiou_Model_Version();
            $download->download($platform, $type);
        }
    }


    public function vCodeAction() {
        $this->view->setNoRender(true);
        //开启session
//        session_start();
        //构造方法
        $vcode = new Weiou_Util_Vcode(90, 44, 4);
        //将验证码放到服务器自己的空间保存一份
        $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
        $sessionUtil->set("vCode", $vcode->getcode());
//        $_SESSION['code'] = $vcode->getcode();
        //将验证码图片输出
        $vcode->outimg();
    }

    /**
     * 获取验证码
     */
    public function getSmsCodeAction() {
        $this->view->setNoRender();
        $countryCode = $this->getParam('countryCode');
        $phoneNumber = $this->getParam("phone");

        $result = new Weiou_JsonModel_ResultForIos();

        $vCode = $this->getParam("vCode", "");

        $state = 2;

        $accountService = new Weiou_Service_Account();

        $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
        $vCodeSession = $sessionUtil->get("vCode");

        if ($vCodeSession != "" && strtolower($vCodeSession) == strtolower($vCode)) {
            $re = $accountService->sendMessauthcode($countryCode, $phoneNumber);
//            $re = "saa";
            if ($re != "") {
                $state = 0;
            } else {
                $state = 2;
            }
        } else {
            $state = 1;
        }

        $result->state = $state;
        echo $result->toJson();
    }

    public function testAction() {
        $this->view->setNoRender();
        $locUtil = new Weiou_Util_Loc();
        $gpsLat = $this->getParam('lat');
        $gpsLng = $this->getParam('lng');

        if ($gpsLat && $gpsLng) {
            $locLat = $gpsLat;
            $locLng = $gpsLng;
            $amapLat = $gpsLat;
            $amapLng = $gpsLng;
        } else {
            
            echo "lat: lng:";
            return;
        }
        
        
        $adr = $locUtil->reverseGeocodingByAmap($amapLat, $amapLng);
        print_r($adr);
        echo "<hr>";
        
        $rg = $locUtil->reverseGeocoding($locLat, $locLng);
                print_r($rg);
                echo "<hr>";


        /*
         * 国外使用gps，不需要转换
         */
        $gpsToBaiduMap = $locUtil->gpsConvBaiduMap($gpsLat,$gpsLng);
        print_r($gpsToBaiduMap);
        echo "<hr>";
        $this->logger->info($gpsToBaiduMap);
        $insertArr['formatted_address'] = "";
        if ($gpsToBaiduMap['state'] == 1) {//返回0，无法解析，说明是国外的gps
            $gpsToAmap = $locUtil->gpsToAmap($gpsLat, $gpsLng);
            print_r($gpsToAmap);
            echo "<hr>";
            $this->logger->info($gpsToAmap);
            if ($gpsToAmap['state'] == 1) {
                $amapLng = $gpsToAmap['lng'];
                $amapLat = $gpsToAmap['lat'];
            } else {
                $amapLng = $locLng - 0.0065;
                $amapLat = $locLat - 0.0060;
            }
            $locLng = $gpsToBaiduMap['lng'];
            $locLat = $gpsToBaiduMap['lat'];


//            $adr = $locUtil->reverseGeocodingByAmap($amapLat, $amapLng);
//            print_r($adr);
//            echo "<hr>";
            
            
            if (is_string($adr->regeocode->formatted_address) && $adr->regeocode->formatted_address != "") {
                $insertArr['province_name'] = $adr->regeocode->addressComponent->province;
                $insertArr['city_name'] = $adr->regeocode->addressComponent->city;
                if (is_string($adr->regeocode->addressComponent->city) && $adr->regeocode->addressComponent->city != "") {
                    $insertArr['city_name'] = $adr->regeocode->addressComponent->province;
                }
                $insertArr['county_name'] = $adr->regeocode->addressComponent->district;
                $insertArr['street_name'] = $adr->regeocode->addressComponent->township;
                $insertArr['formatted_address'] = $adr->regeocode->formatted_address;
            } else {
//                $rg = $locUtil->reverseGeocoding($locLat, $locLng);
//                print_r($rg);
//                echo "<hr>";
                if ($rg->result->formatted_address != "") {
                    $insertArr['province_name'] = $rg->result->addressComponent->province;
                    $insertArr['city_name'] = $rg->result->addressComponent->city;
                    $insertArr['county_name'] = $rg->result->addressComponent->district;
                    $insertArr['street_name'] = $rg->result->addressComponent->street;
                    $insertArr['formatted_address'] = $rg->result->formatted_address;
                }
            }
        }
        
        
        $reverseGeocodeResult = $locUtil->reverseGeocodingGoogle($gpsLat,$gpsLng);
        print_r($reverseGeocodeResult);
        echo "<hr>";
        if ($insertArr['formatted_address'] == "") {
            
//            $reverseGeocodeResult = $locUtil->reverseGeocodingGoogle($gpsLat,$gpsLng);
//            print_r($reverseGeocodeResult);
//            echo "<hr>";
            
            if ($reverseGeocodeResult['status'] == 1) {
                $insertArr['formatted_address'] = $reverseGeocodeResult['formatted_address'];
            }
        }
        print_r($insertArr);
        echo "<hr>";
    }

}
