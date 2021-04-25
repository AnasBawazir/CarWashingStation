<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class PaymentSettingController extends Controller
{
    public function update_payment_setting(Request $request)
    {
        $data = $request->all();
        $id = PaymentSetting::find(1);
        if(isset($data['cod']))
        {
            $data['cod'] = 1;
        }
        else
        {
            $data['cod'] = 0;
        }
        if(isset($data['paypal']))
        {
            $request->validate([
                'paypal_production' => 'required',
                'paypal_sendbox' => 'required'
            ]);
            $data['paypal'] = 1;
        }
        else
        {
            $data['paypal'] = 0;
        }
        if(isset($data['razorpay']))
        {
            $request->validate([
                'razorpay_key' => 'required',
            ]);
            $data['razorpay'] = 1;
        }
        else
        {
            $data['razorpay'] = 0;
        }
        if(isset($data['stripe']))
        {
            $request->validate([
                'stripe_publish_key' => 'required',
                'stripe_secret_key' => 'required',
            ]);
            $data['stripe'] = 1;
        }
        else
        {
            $data['stripe'] = 0;
        }
        $id->update($data);
        return redirect('admin/setting');
    }
}
