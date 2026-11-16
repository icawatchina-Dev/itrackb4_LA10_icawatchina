<?php
namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'municipalityCount' => Municipality::count(),
            'brgyCount' => Barangay::count(),
        ]);
    }
}
