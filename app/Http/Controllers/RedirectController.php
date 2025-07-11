<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransRouter;

class RedirectController extends Controller
{
    public function handle(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->to('https://delitekno.co.id');
        }

        $target = MidtransRouter::resolveTargetSystem($orderId);

        if ($target) {
            return redirect()->to($target . '?order_id=' . $orderId);
        }

        return redirect()->to('https://delitekno.co.id');
    }
}
