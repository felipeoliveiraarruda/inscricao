<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Edital;
use App\Models\Utils;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {        
        $editais = Edital::where('dataFinalEdital', '>',  Carbon::now())->get();

        if (Auth::guest())
        {
            return view('index', 
            [
                'editais' => $editais,
                'utils'   => new Utils,        
            ]);
        }
        else
        {
            return redirect('dashboard');
        }
    }
}
