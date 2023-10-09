<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Link $link)
    {
        if ($link->is_active != true) {
            $message = [
                'status' => '402',
                'statusText' => 'خطا',
                'message' => __('message.link_not_active'),
            ];
            return view('error')->with('message', $message);
        }
        if ($link->is_scheduled) {
            $current_time = date('Y-m-d H:i:s');
            if ($current_time < $link->start_date || $current_time > $link->end_date) {
                $message = [
                    'status' => '402',
                    'statusText' => 'خطا',
                    'message' => __('message.link_is_schedule_error'),
                    'data' => [
                        'اعتبار لینک از تاریخ ' . verta($link->start_date) . ' تا تاریخ ' . verta($link->end_date) . ' می‌باشد.'
                    ]
                ];
                return view('error')->with('message', $message);
            }
        }
        if ($link->max_uses){
            // todo max use
        }
        $drivers = Gateway::active()->get();
        return view('link.link', compact(['drivers', 'link']));
    }

}
