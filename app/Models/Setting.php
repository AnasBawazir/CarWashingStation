<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['company_name','push_notification','mail_notification','sms_verification','mail_verification','company_logo','company_favicon','company_address','phone','website','currency','currency_symbol','service_at_home','latitude','longitude','user_verification','onesignal_app_id','onesignal_auth_key','rest_api_key','project_number','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','twilio_acc_id','twilio_auth_token','twilio_phone_no','privacy_policy','map_key','color','license_code','client_name','license_verify','coworker_notification','coworker_app_id','coworker_auth_key','coworker_rest_api_key','coworker_project_number'];

}
