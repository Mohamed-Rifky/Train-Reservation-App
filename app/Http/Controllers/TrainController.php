<?php

namespace App\Http\Controllers;

use App\Models\Trains;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class TrainController extends Controller
{
    public function trains(Request $request){
        return view('trains.trains');
    }
    public function getTrains(Request $request){
        $method = $request->method();
        if ($method === 'POST') {
            $trains = Trains::query();
            if($request->search){
                $trains->where('train_name', 'LIKE', "%{$request->search}%");
            }
            $trains = $trains->paginate(10);
        } else {
            $trains = Trains::paginate(10);
        }
        if($trains){
            foreach ($trains as $train){
                $trainDateTimeObj = Carbon::parse($train->departure_date_time);
                $train->date = $trainDateTimeObj->format('Y-m-d');
                $train->time = $trainDateTimeObj->format('h:i:s A');
            }
        }
        return $trains;
    }
    public function addTrain(Request $request){
        $rules = [
            'name' => 'required',
            'departure_time_date' => 'required',
            'no_of_seats' => 'required|numeric|max:10',
        ];
        $messages = [
            'name.required' => 'Please Enter Name',
            'departure_time_date.required' => 'Please Enter Date Time',
            'no_of_seats.required' => 'Please Enter No Of Seats ',
        ];
        $Validator = Validator::make($request->all(), $rules, $messages);
        if ($Validator->fails()) {
            $messages = $Validator->messages();
            $Status['error'] = $messages;
            $Status['status'] = false;
            return $Status;
        }
        $train = new Trains();
        $train->train_name = $request->name;
        $train->departure_date_time = $request->departure_time_date;
        $train->no_of_seats = $request->no_of_seats;
        $train->save();

        return  $train;
    }
    public function getTrain(Request $request){
        $id = $request->id;
        $train = Trains::findOrFail($id);
        return $train;
    }
    public function updateTrain(Request $request){
        $rules = [
            'name' => 'required',
            'departure_time_date' => 'required',
            'no_of_seats' => 'required|numeric|max:10',
        ];
        $messages = [
            'name.required' => 'Please Enter Name',
            'departure_time_date.required' => 'Please Enter Date Time',
            'no_of_seats.required' => 'Please Enter No Of Seats ',
        ];
        $Validator = Validator::make($request->all(), $rules, $messages);
        if ($Validator->fails()) {
            $messages = $Validator->messages();
            $Status['error'] = $messages;
            $Status['status'] = false;
            return $Status;
        }
        $train = Trains::find($request->id);
        $train->train_name = $request->name;
        $train->departure_date_time = $request->departure_time_date;
        $train->no_of_seats = $request->no_of_seats;
        $train->save();

        return  $train;
    }
}
