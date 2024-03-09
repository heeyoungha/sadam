<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Board;
use Illuminate\Database\Query\Builder;
use Auth;
use App\Policies\BoardPolicy;

class BookmarkController extends Controller
{
    
    public function index(Request $request) {
        
        $user = Auth::user();
        if ($request->user()->cannot('view', Bookmark::class)) {
            abort(403, '죄송합니다. 조회 권한이 없습니다.');
        }
        
        $query = Board::leftJoin('users as u', 'boards.user_id', '=', 'u.id')
        ->select('boards.*', 'u.id as u_id', 'u.name as uname')
        ->leftJoinSub(function (Builder $query) {
            $query->select('board_id', \DB::raw('count(*) as reaction_count'))
                ->from('board_reactions')
                ->groupBy('board_id');
        }, 'reactions', 'boards.id', '=', 'reactions.board_id')
        ->selectRaw('IFNULL(reactions.reaction_count, 0) as reaction_count')
        ->having('reaction_count', '>', 0)
        ->orderByDesc('reaction_count');

        $searchTitle = $request->get('searchTitle');
        if ($searchTitle) {
            $query->where('u.name', 'like', "%$searchTitle%");
        }

        $filter = $request->get('filter');

        $query->orderByDesc('reaction_count')
        ->orderBy('boards.created_at', 'desc');

        $table_data = $query->get();

        $filteredCollection = collect($table_data);
        $filteredArray = $filteredCollection->all();
        
        $countCollection = count($filteredCollection);
        $newCollection = collect($filteredArray);

        return view('bookmark.index', compact('user','searchTitle','filter','countCollection','newCollection'));
    }
}
