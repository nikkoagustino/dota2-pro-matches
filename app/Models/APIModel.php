<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class APIModel extends Model
{
    use HasFactory;

    static function addProMatches($req) {
        $insert = DB::table('pro_matches')
                    ->insert([
                        "match_id" => $req->match_id,
                        "duration" => $req->duration,
                        "start_time" => $req->start_time,
                        "radiant_team_id" => $req->radiant_team_id,
                        "radiant_name" => $req->radiant_name,
                        "dire_team_id" => $req->dire_team_id,
                        "dire_name" => $req->dire_name,
                        "leagueid" => $req->leagueid,
                        "league_name" => $req->league_name,
                        "series_id" => $req->series_id,
                        "series_type" => $req->series_type,
                        "radiant_score" => $req->radiant_score,
                        "dire_score" => $req->dire_score,
                        "radiant_win" => $req->radiant_win,
                    ]);
        return $insert;
    }

    static function proMatchesLastID() {
        $result = DB::table('pro_matches')
                    ->orderBy('match_id', 'asc')
                    ->first();
        return $result->match_id;
    }

    static function getProMatchesCount() {
        $result = DB::table('pro_matches')->count();
        return $result;
    }
    static function getProMatchesCountNoHero() {
        $result = DB::table('pro_matches')
                    ->where('radiant_hero_1', '=', null)
                    ->count();
        return $result;
    }

    static function getMatchWithoutHeroRand() {
        $result = DB::table('pro_matches')
                    ->where('radiant_hero_1', '=', null)
                    ->inRandomOrder()
                    ->first();
        return $result;
    }
    static function getMatchWithoutHero() {
        $result = DB::table('pro_matches')
                    ->where('radiant_hero_1', '=', null)
                    ->first();
        return $result;
    }

    static function getMatchWithoutHeroDesc() {
        $result = DB::table('pro_matches')
                    ->where('radiant_hero_1', '=', null)
                    ->orderBy('match_id', 'asc')
                    ->first();
        return $result;
    }

    static function updateHeroName($match_id, $radiant_hero, $dire_hero) {
        $update = DB::table('pro_matches')
                    ->where('match_id', '=', $match_id)
                    ->update([
                        'radiant_hero_1' => $radiant_hero[1],
                        'radiant_hero_2' => $radiant_hero[2],
                        'radiant_hero_3' => $radiant_hero[3],
                        'radiant_hero_4' => $radiant_hero[4],
                        'radiant_hero_5' => $radiant_hero[5],
                        'dire_hero_1' => $dire_hero[1],
                        'dire_hero_2' => $dire_hero[2],
                        'dire_hero_3' => $dire_hero[3],
                        'dire_hero_4' => $dire_hero[4],
                        'dire_hero_5' => $dire_hero[5],
                    ]);
        return $update;
    }
}
