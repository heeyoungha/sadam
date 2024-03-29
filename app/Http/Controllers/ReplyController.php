<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reply;
use App\Models\ReplyReaction;
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

    public function replyReaction($board_id, $reply_id, Request $request){
        try{
            DB::beginTransaction(); 
            $user = User::find($request->user_id);
            
            $replyReaction = ReplyReaction::where('user_id', $user->id)
                    ->where('reply_id', $reply_id)
                    ->first();

            if ($replyReaction) {
                $replyReaction->delete();
            } else {
                $replyReaction = new ReplyReaction();
                $replyReaction->user_id = $user->id;
                $replyReaction->reply_id = $reply_id;
                $replyReaction->board_id = $board_id;
                $replyReaction->type = 'like';
                $replyReaction->save();
            }

            $likeCnt = ReplyReaction::where('reply_id',$reply_id)->where('type','like')->count();
            $likeCnt = $likeCnt === 0 ? '' : $likeCnt;
            DB::commit();

            return response()->json([
                'success' => true, 
                'likeCnt' => $likeCnt,
                'message' => 'Board reaction updated successfully'
            ]);
            
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
