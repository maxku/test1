<?php

namespace App\Http\Controllers;

use App\Record;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class RecordController extends Controller
{

    // Check input values
    public function check_validator($input)
    {
        $rules = [
            'description' => 'required',
            'value'       => 'required|numeric',
            'date'        => 'date',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return [
                'fail'   => true,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    // Function to exchange currencies
    public function get_exchange_value()
    {
        // PrivatBank API json
        $url
            = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
        $exchanges = json_decode(file_get_contents($url), true);
        $ex = 0;
        foreach ($exchanges as $exchange) {
            if ($exchange['ccy'] == 'USD') {
                $ex = $exchange['buy'];
            }
        }
        return $ex;
    }

    // Home page
    public function getIndex()
    {
        return view('index');
    }

    // Builds list of records
    public function getList()
    {
        // Check if there are input values, otherwise check session values
        Session::put('date_from', Input::has('ok') ? Input::get('date_from')
            : (Session::has('date_from') ? Session::get('date_from') : ''));
        Session::put('date_to', Input::has('ok') ? Input::get('date_to')
            : (Session::has('date_to') ? Session::get('date_to') : ''));
        Session::put('record_field', Input::has('field')
            ? Input::get('field')
            : (Session::has('record_field') ? Session::get('record_field')
                : 'date'));
        Session::put('record_sort', Input::has('sort')
            ? Input::get('sort')
            : (Session::has('record_sort') ? Session::get('record_sort')
                : 'desc'));

        // Set base time
        $from = Carbon::minValue();
        $to = Carbon::maxValue();

        // "From" time must be 00:00:00
        if (Session::get('date_from') != '') {
            $from = Carbon::parse(Session::get('date_from'));
        }
        // "To" time must be 23:59:59
        if (Session::get('date_to') != '') {
            $to = Carbon::parse(Session::get('date_to'))->addHours(24)
                ->subSecond();
        }

        $records = Record::whereBetween('date', [$from, $to])
            ->orderBy(Session::get('record_field'), Session::get('record_sort'))
            ->paginate(50);

        return view('list', ['records' => $records]);
    }

    public function getUpdate($id)
    {
        return view('update', ['record' => Record::find($id)]);
    }

    // Update record in DB
    public function postUpdate($id)
    {
        // Validate
        if (is_array($res = RecordController::check_validator(Input::all()))) {
            return $res;
        }

        $record = Record::find($id);

        $record->description = Input::get('description');
        $record->value = Input::get('value');
        $record->value_usd = Input::get('value') *
            RecordController::get_exchange_value() / 100;
        $record->date = Input::get('date') ?
            Input::get('date') : Carbon::now();

        $record->save();

        return ['url' => '/list'];
    }

    public function getCreate()
    {
        return view('create');
    }

    // Create new record in DB
    public function postCreate()
    {
        // Validate
        if (is_array($res = RecordController::check_validator(Input::all()))) {
            return $res;
        }

        $record = new Record;

        $record->description = Input::get('description');
        $record->value = Input::get('value');
        $record->value_usd = Input::get('value') *
            RecordController::get_exchange_value() / 100;
        $record->date = Input::get('date') ?
            Input::get('date') : Carbon::now();

        $record->save();

        return ['url' => '/list'];
    }

    // Delete record from DB
    public function getDelete($id)
    {
        Record::find($id)->delete();
        return Redirect('/list');
    }

}
