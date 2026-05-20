<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $audits = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('audits.index', compact('audits'));
    }
}
