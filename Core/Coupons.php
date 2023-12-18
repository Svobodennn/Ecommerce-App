<?php

namespace Core;

class Coupons
{
    private function generateCouponCode() {
        do {
            // İki sayı arasında en az 3 adet T harfi bulunan rastgele bir string oluştur
            $randomString = $this->generateRandomStringWithT(13); // 13 karakter uzunluğunda
        } while (!$this->isValidCoupon($randomString));
                // oluşturulan stringi kontrol et

        $coupon = $randomString;

        return $coupon;
    }

    private function isValidCoupon($coupon) {
        // İki sayı arasında en az 3 adet T olacak

        if (strlen($coupon) >13){
            return false;
        }

        $pattern = '/^[a-zA-Z0-9]*\d+T{3,}\d+[a-zA-Z0-9]*$/';

        return preg_match($pattern, $coupon) === 1;
    }

    private function generateRandomStringWithT($length) {
        // Boş bir string yarattık ve T sayısı için değişken oluşturduk
        $randomString = '';
        $tCount = 0;

        while (strlen($randomString) < $length) {
            $randomChar = rand(0, 1) ? 'T' : rand(0, 9);

            if ($randomChar === 'T') {
                $tCount++;
                if ($tCount >= 3) {
                    $randomString .= 'TTT'; // 3 T harfini yerleştir
                    $tCount = 0;
                }
            } elseif (is_numeric($randomChar)) {
                $randomString .= $randomChar;
                $tCount = 0; // T gelmezse sıfırla
            } else {
                $tCount = 0;
                $randomString .= $randomChar;
            }
        }

        return $randomString;
    }


    public function generateCoupons($count)
    {
        $coupons = [];

        for ($i = 0; $i < $count; $i++) {
            $coupon = $this->generateCouponCode();
            $coupons[] = $coupon;
        }

        return $coupons;
    }

}