<?php

namespace App\Http\Controllers;

use App\Models\TrainBookings;
use Illuminate\Http\Request;
use Validator;
class PublicAreaController extends Controller
{
    public function viewTrains(){
        return view('trains.booking_train');
    }
    public function addReservation(Request $request){
        $rules = [
            'nic' => 'required',
            'name' => 'required',
            'contact_no' => 'required|numeric',
        ];
        $messages = [
            'nic.required' => 'Please Enter NIC',
            'name.required' => 'Please Enter Name',
            'contact_no.required' => 'Please Enter Contact No',
        ];
        $Validator = Validator::make($request->all(), $rules, $messages);
        if ($Validator->fails()) {
            $messages = $Validator->messages();
            $Status['error'] = $messages;
            $Status['status'] = false;
            return $Status;
        }
        if(!TrainBookings::where('nic',$request->nic)->where('train_id',$request->train_id)->exists()) {
            $bookings = new TrainBookings();
            $bookings->train_id = $request->train_id;
            $bookings->nic = $request->nic;
            $bookings->name = $request->name;
            $bookings->contact_no = $request->contact_no;
            $bookings->save();
            return $bookings;
        } else {
            $messages['nic'][0] = 'This NIC Already Booked for This Train';
            $Status['error'] = $messages;
            $Status['status'] = false;
            return $Status;
        }
    }
}
