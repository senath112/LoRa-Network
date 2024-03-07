// app/Http/Controllers/FirstPageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FirstPageController extends Controller
{
    public function index(Request $request)
    {
        // Get UID from the HTTP GET request
        $uid = $request->query('uid');

        // Check if UID exists in credits table
        $credits = DB::table('credits')->where('UID', $uid)->value('creditsR');

        // Check if credits are greater than 1
        $response = ($credits > 1) ? '1' : '0';

        // Send response
        return response()->json($response);
    }
}
