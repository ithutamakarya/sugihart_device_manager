<?php

namespace Sugihart\DeviceManager\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sugihart\DeviceManager\Models\LogActivity;

class BaseCoreController extends Controller
{
    protected function saveWithLog(Model $model, array $data, string $action, string $resource): void
    {
        DB::connection('sdm')->transaction(function () use ($model, $data, $action, $resource): void {
            $model->fill($data)->save();
            LogActivity::create([
                'user_device' => auth('sdm')->user()->name,
                'activity' => 'Melakukan '.$action.' Data '.$resource,
                'ip_address' => request()->ip() ?? 'UNKNOWN',
            ]);
        });
        session()->flash('sdm.message_alert', 'Data Berhasil Di '.$action);
        session()->flash('sdm.status_alert', 'success');
    }
}
