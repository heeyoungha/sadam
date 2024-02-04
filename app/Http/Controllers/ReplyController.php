<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reply;
use Auth;

class ReplyController extends Controller
{
    public function store($board_id, Request $request){

        try{
            DB::beginTransaction();
        
            $user = Auth::user();

            Reply::create([
                'user_id' => $user->id,
                'board_id' => $board_id,
                'content' => $request->content,
                'reply_user_name' => $user->name
            ]);

            DB::commit();
            return redirect()->back();

        } catch (\Exception $e){
            DB::rollback();
            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
    }
}
