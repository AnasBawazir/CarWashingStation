<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'payment_settings';

    protected $fillable = ['cod','paypal','stripe','razorpay','paypal_production','paypal_sendbox','stripe_publish_key','stripe_secret_key','razorpay_key'];

}
