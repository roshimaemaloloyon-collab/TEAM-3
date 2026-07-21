<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuccessionController extends Controller
{
    public function leadership(Request $request)
    {
        return view('admin.succession.leadership');
    }

    public function careerPath(Request $request)
    {
        return view('admin.succession.career-path');
    }

    public function developmentPlan(Request $request)
    {
        return view('admin.succession.development-plan');
    }

    public function promotionReadiness(Request $request)
    {
        return view('admin.succession.promotion-readiness');
    }

    public function successionHistory(Request $request)
    {
        return view('admin.succession.succession-history');
    }

    public function talentPool(Request $request)
    {
        return view('admin.succession.talent-pool');
    }
}
