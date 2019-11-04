<?php


namespace App\Http\Controllers;

use App\Model\Reporter;
use App\Model\Runtime;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
class SystemUsage extends BaseController
{
    public function SystemRun(Request $request){
        $api_key = $request->input('api_key');
        if($api_key === NULL || $api_key !== env(Runtime::SYSTEM_KEY_ID)){
            return response()->json([
                'status' => Reporter::ERROR_STATUS,
                'message' => Reporter::UNAUTHORIZED_MESSAGE
            ], 403);
        }
        Runtime::getInstance()->execute();
        return response()->json([
            'status' => Reporter::SUCCESS_STATUS,
            'message' => Reporter::SYSTEM_RUN_MESSAGE
        ]);
    }
}
