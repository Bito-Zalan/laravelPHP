<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Dotenv\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReservationController extends Controller
{
    //könyvtáros
    public function index()
    {
        return Reservation::all();
    }

    /**
     * Store a newly created resource in storage.
     */

     //konyvtáros
    public function store(Request $request)
    {
        $record = new Reservation();
        $record->fill($request->all());
        $record->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id, string $book_id, string $start)
    {
        $reservation = Reservation::where("user_id", $user_id)
        ->where('book_id',$book_id)
        ->where('start',$start)
        ->get();
        return $reservation[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $book_id, string $user_id, string $start)
    {
        $reservation = Reservation::find($user_id,$book_id,$start);
        $reservation->fill($request->all());
        $reservation->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id,string $book_id, string $start)
    {
        $this->show($user_id,$book_id,$start)->delete();
    }

    //spec lekérdezések
    public function reservedBooks(){
        //Ha nincs bejelentett fh, akkor $user,->where sor hiányzik
        $user = Auth::user();
        return Reservation::with('books')
        ->where('user_id', $user->id)
        ->get();
    }
    public function userReservationData(){
        $user = Auth::user();
        return Reservation::with('users')
        ->where('user_id', $user->id)
        ->get();
    }

    // hány db előjegyzése van a bejelentkezett felhasználóknak?
    public function reservedCount(){
        $user = Auth::user();
        $pieces = DB::table("reservations")
        ->where('user_id',$user->id)
        ->count();
        return $pieces;
    }

}
