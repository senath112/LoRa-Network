// app/Http/Controllers/SecondPageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SecondPageController extends Controller
{
    public function index(Request $request)
    {
        // Get UID and URL from the HTTP GET request
        $uid = $request->query('uid');
        $url = $request->query('url');

        // Make HTTP request to the URL
        $client = new Client();
        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
            } else {
                $statusCode = 400;
            }
        }

        // Save values in Report table
        DB::table('report')->insert([
            'UID' => $uid,
            'Timestamp' => now(),
            'gateway' => 'http',
            'url' => $url,
            'status' => $statusCode
        ]);

        // Send response
        return response()->json($statusCode);
    }
}
