<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Spatie\Permission\Models\Permission;

class SettingController extends Controller
{
    public function setting()
    {
        abort_if(Gate::denies('admin_setting'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company_setting = Setting::find(1);
        $currencies = Currency::all();
        $payment_setting = PaymentSetting::find(1);
        return view('admin.setting.setting',compact('currencies','company_setting','payment_setting'));
    }

    public function update_setting(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'company_name' => 'required',
            'website' => 'required',
            'phone' => 'bail|required|numeric|digits:10',
            'company_address' => 'required',
            'currency' => 'required',
            'map_key' => 'required',
        ]);
        $id = Setting::find(1);

        if ($file = $request->hasfile('company_logo'))
        {
            $file = $request->file('company_logo');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['company_logo'] = $fileName;
        }

        if ($file = $request->hasfile('company_favicon')) {
            $file = $request->file('company_favicon');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload';
            $file->move($path, $fileName);
            $data['company_favicon'] = $fileName;
        }
        $symbol = Currency::where('code',$data['currency'])->first();
        $data['currency_symbol'] = $symbol->symbol;
        $id->update($data);
        return redirect('admin/setting');
    }

    public function update_notification_setting(Request $request)
    {
        $id = Setting::find(1);
        $request->validate([
            'mail_host' => 'required',
            'mail_port' => 'required|regex:/^\S*$/u',
            'mail_username' => 'required|regex:/^\S*$/u',
            'mail_password' => 'required|regex:/^\S*$/u',
            'mail_encryption' => 'required|regex:/^\S*$/u',
            'mail_from_address' => 'required|regex:/^\S*$/u',
        ]);
        $data = $request->all();
        if(isset($data['push_notification']))
        {
            $request->validate([
                'onesignal_app_id' => 'required|regex:/^\S*$/u',
                'rest_api_key' => 'required|regex:/^\S*$/u',
                'onesignal_auth_key' => 'required|regex:/^\S*$/u',
                'project_number' => 'required|regex:/^\S*$/u',
            ]);

            $data['push_notification'] = 1;
            $onesignal['APP_ID']=$request->onesignal_app_id;
            $onesignal['REST_API_KEY'] = $request->rest_api_key;
            $onesignal['USER_AUTH_KEY'] = $request->onesignal_auth_key;
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($onesignal) > 0)
            {
                foreach ($onesignal as $envKey => $envValue)
                {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            file_put_contents($envFile, $str);
        }
        else
        {
            $data['push_notification'] = 0;
        }

        if(isset($data['mail_notification']))
        {
            $data['mail_notification'] = 1;
        }
        else
        {
            $data['mail_notification'] = 0;
        }

        $mail['MAIL_HOST']=$request->mail_host;
        $mail['MAIL_PORT'] = $request->mail_port;
        $mail['MAIL_USERNAME'] = $request->mail_username;
        $mail['MAIL_PASSWORD'] = $request->mail_password;
        $mail['MAIL_ENCRYPTION'] = $request->mail_encryption;
        $mail['MAIL_FROM_ADDRESS'] = $request->mail_from_address;
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($mail) > 0)
        {
            foreach ($mail as $envKey => $envValue)
            {
                $str .= "\n";
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                if (!$keyPosition || !$endOfLinePosition || !$oldLine)
                {
                    $str .= "{$envKey}={$envValue}\n";
                }
                else
                {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        file_put_contents($envFile, $str);
        $id->update($data);
        return redirect('admin/setting');
    }

    public function update_coworker_notification_setting(Request $request)
    {
        $id = Setting::find(1);
        $data = $request->all();
        if(isset($data['coworker_notification']))
        {
            $request->validate([
                'coworker_app_id' => 'required|regex:/^\S*$/u',
                'coworker_rest_api_key' => 'required|regex:/^\S*$/u',
                'coworker_auth_key' => 'required|regex:/^\S*$/u',
                'coworker_project_number' => 'required|regex:/^\S*$/u',
            ]);

            $data['coworker_notification'] = 1;
            $onesignal['COWORKER_APP_ID']=$request->coworker_app_id;
            $onesignal['COWORKER_REST_API_KEY'] = $request->coworker_rest_api_key;
            $onesignal['COWORKER_AUTH_KEY'] = $request->coworker_auth_key;
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($onesignal) > 0)
            {
                foreach ($onesignal as $envKey => $envValue)
                {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            file_put_contents($envFile, $str);
        }
        else
        {
            $data['coworker_notification'] = 0;
        }
        $id->update($data);
        return redirect('admin/setting');
    }

    public function update_user_verification(Request $request)
    {
        $data = $request->all();

        if(isset($data['user_verification']))
        {
            if(isset($data['sms_verification']) || isset($data['mail_verification']))
            {
                $data['user_verification'] = 1;
            }
            else
            {
                return redirect()->back()->withErrors('At least select one mail or sms');
            }
        }
        else
        {
            $data['user_verification'] = 0;
        }

        if(isset($data['sms_verification']))
        {
            $request->validate([
                'twilio_acc_id' => 'required',
                'twilio_auth_token' => 'required|regex:/^\S*$/u',
                'twilio_phone_no' => 'required|regex:/^\S*$/u',
            ]);
            $data['sms_verification'] = 1;
        }
        else
        {
            $data['sms_verification'] = 0;
        }

        if(isset($data['mail_verification']))
        {
            $data['mail_verification'] = 1;
        }
        else
        {
            $data['mail_verification'] = 0;
        }
        $id = Setting::find(1);
        $id->update($data);
        return redirect('admin/setting');
    }

    public function update_privacy_policy(Request $request)
    {
        $id = Setting::find(1);
        $id->update($request->all());
        return redirect('admin/setting');
    }
}
