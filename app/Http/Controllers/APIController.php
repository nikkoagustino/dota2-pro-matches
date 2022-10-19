<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APIModel;

class APIController extends Controller
{
    protected $endpoint;

    function __construct() {
        $this->endpoint = 'https://api.opendota.com/api';
    }

    public function callAPI($method, $url, $data) {
        if ($method == 'POST') {
            $curl = curl_init($this->endpoint.$url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // $send_header = array('Content-Type: application/json', 'x-api-key: '.$this->shyft_api_key);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, $send_header);

            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);
            return $result;
        } else if ($method == 'GET') {
            $getdata = '';
            foreach ($data as $key => $value) {
                if ($getdata != '') {
                    $getdata = $getdata.'&';
                }
                $getdata = $getdata.$key.'='.$value;
            }
            $combined_url = $this->endpoint.$url.'?'.$getdata;
            $curl = curl_init($combined_url);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // $send_header = array('Content-Type: application/json', 'x-api-key: '.$this->shyft_api_key);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, $send_header);

            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);
            return $result;
        } else if ($method == 'GETBODY') {
            $combined_url = $this->endpoint.$url;
            $curl = curl_init($combined_url);
            curl_setopt($curl, CURLOPT_POST, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // $send_header = array('Content-Type: application/json', 'x-api-key: '.$this->shyft_api_key);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, $send_header);

            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);
            return $result;
        }
    }


    public function getOlderProMatches() {
        // $data = [
        //     'less_than_match_id' => 9999999999999999999999,
        // ];
        // $result = $this->callAPI('GET', '/proMatches', $data);
        $latest_id = APIModel::proMatchesLastID();
        $result = json_decode(file_get_contents('https://api.opendota.com/api/proMatches?less_than_match_id='.$latest_id));
        foreach ($result as $row) {
            APIModel::addProMatches($row);
        }
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
        // echo "
        // <script>
        // setInterval(window.location.reload(), 3000);
        // </script>
        // ";
    }

    public function getProMatchesCount() {
        echo "All data = ".APIModel::getProMatchesCount();
        echo "<br>";
        echo "No hero = ".APIModel::getProMatchesCountNoHero();
    }

    public function getHeroes() {
            echo $this->getProMatchesCount();
        $matches = APIModel::getMatchWithoutHeroRand();
        echo "<br><br><br><h2>".$matches->match_id."</h2>";
        $matchdata = json_decode(file_get_contents('https://api.opendota.com/api/matches/'.$matches->match_id));

        $heroname = json_decode('{"1":"Anti-Mage","2":"Axe","3":"Bane","4":"Bloodseeker","5":"Crystal Maiden","6":"Drow Ranger","7":"Earthshaker","8":"Juggernaut","9":"Mirana","10":"Morphling","11":"Shadow Fiend","12":"Phantom Lancer","13":"Puck","14":"Pudge","15":"Razor","16":"Sand King","17":"Storm Spirit","18":"Sven","19":"Tiny","20":"Vengeful Spirit","21":"Windranger","22":"Zeus","23":"Kunkka","25":"Lina","26":"Lion","27":"Shadow Shaman","28":"Slardar","29":"Tidehunter","30":"Witch Doctor","31":"Lich","32":"Riki","33":"Enigma","34":"Tinker","35":"Sniper","36":"Necrophos","37":"Warlock","38":"Beastmaster","39":"Queen of Pain","40":"Venomancer","41":"Faceless Void","42":"Wraith King","43":"Death Prophet","44":"Phantom Assassin","45":"Pugna","46":"Templar Assassin","47":"Viper","48":"Luna","49":"Dragon Knight","50":"Dazzle","51":"Clockwerk","52":"Leshrac","53":"Natures Prophet","54":"Lifestealer","55":"Dark Seer","56":"Clinkz","57":"Omniknight","58":"Enchantress","59":"Huskar","60":"Night Stalker","61":"Broodmother","62":"Bounty Hunter","63":"Weaver","64":"Jakiro","65":"Batrider","66":"Chen","67":"Spectre","68":"Ancient Apparition","69":"Doom","70":"Ursa","71":"Spirit Breaker","72":"Gyrocopter","73":"Alchemist","74":"Invoker","75":"Silencer","76":"Outworld Devourer","77":"Lycan","78":"Brewmaster","79":"Shadow Demon","80":"Lone Druid","81":"Chaos Knight","82":"Meepo","83":"Treant Protector","84":"Ogre Magi","85":"Undying","86":"Rubick","87":"Disruptor","88":"Nyx Assassin","89":"Naga Siren","90":"Keeper of the Light","91":"Io","92":"Visage","93":"Slark","94":"Medusa","95":"Troll Warlord","96":"Centaur Warrunner","97":"Magnus","98":"Timbersaw","99":"Bristleback","100":"Tusk","101":"Skywrath Mage","102":"Abaddon","103":"Elder Titan","104":"Legion Commander","105":"Techies","106":"Ember Spirit","107":"Earth Spirit","108":"Underlord","109":"Terrorblade","110":"Phoenix","111":"Oracle","112":"Winter Wyvern","113":"Arc Warden","114":"Monkey King","119":"Dark Willow","120":"Pangolier","121":"Grimstroke","123":"Hoodwink","126":"Void Spirit","128":"Snapfire","129":"Mars","135":"Dawnbreaker","136":"Marci","137":"Primal Beast"}');

        $rd = 1; $dr = 1;

        // if ($matchdata->picks_bans == null) {
            $radiant_hero[1] = $heroname->{$matchdata->players[0]->hero_id};
            $radiant_hero[2] = $heroname->{$matchdata->players[1]->hero_id};
            $radiant_hero[3] = $heroname->{$matchdata->players[2]->hero_id};
            $radiant_hero[4] = $heroname->{$matchdata->players[3]->hero_id};
            $radiant_hero[5] = $heroname->{$matchdata->players[4]->hero_id};
            $dire_hero[1] = $heroname->{$matchdata->players[5]->hero_id};
            $dire_hero[2] = $heroname->{$matchdata->players[6]->hero_id};
            $dire_hero[3] = $heroname->{$matchdata->players[7]->hero_id};
            $dire_hero[4] = $heroname->{$matchdata->players[8]->hero_id};
            $dire_hero[5] = $heroname->{$matchdata->players[9]->hero_id};
            var_dump($radiant_hero);
            echo "<br>";
            echo "<br>";
            var_dump($dire_hero);
            APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero);

        // }
        // foreach ($matchdata->picks_bans as $row) {
        //     if ($row->is_pick == true) {
        //         if ($row->team == 0) {
        //             $radiant_hero[$rd] = $heroname->{$row->hero_id};
        //             $rd++;
        //         } else {
        //             $dire_hero[$dr] = $heroname->{$row->hero_id};
        //             $dr++;
        //         }
        //     }
        // }
        // if (APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero)) {
        //     echo "updated ".$matches->match_id;
        //     // echo "
        //     // <script>
        //     // setInterval(window.location.reload(), 3000);
        //     // </script>
        //     // ";
        // }
    }

    public function getHeroesRand() {
            echo $this->getProMatchesCount();
        $matches = APIModel::getMatchWithoutHeroRand();
        echo "<br><br><br><h2>".$matches->match_id."</h2>";
        // $matchdata = json_decode(file_get_contents('https://api.opendota.com/api/matches/'.$matches->match_id));

        // $heroname = json_decode('{"1":"Anti-Mage","2":"Axe","3":"Bane","4":"Bloodseeker","5":"Crystal Maiden","6":"Drow Ranger","7":"Earthshaker","8":"Juggernaut","9":"Mirana","10":"Morphling","11":"Shadow Fiend","12":"Phantom Lancer","13":"Puck","14":"Pudge","15":"Razor","16":"Sand King","17":"Storm Spirit","18":"Sven","19":"Tiny","20":"Vengeful Spirit","21":"Windranger","22":"Zeus","23":"Kunkka","25":"Lina","26":"Lion","27":"Shadow Shaman","28":"Slardar","29":"Tidehunter","30":"Witch Doctor","31":"Lich","32":"Riki","33":"Enigma","34":"Tinker","35":"Sniper","36":"Necrophos","37":"Warlock","38":"Beastmaster","39":"Queen of Pain","40":"Venomancer","41":"Faceless Void","42":"Wraith King","43":"Death Prophet","44":"Phantom Assassin","45":"Pugna","46":"Templar Assassin","47":"Viper","48":"Luna","49":"Dragon Knight","50":"Dazzle","51":"Clockwerk","52":"Leshrac","53":"Natures Prophet","54":"Lifestealer","55":"Dark Seer","56":"Clinkz","57":"Omniknight","58":"Enchantress","59":"Huskar","60":"Night Stalker","61":"Broodmother","62":"Bounty Hunter","63":"Weaver","64":"Jakiro","65":"Batrider","66":"Chen","67":"Spectre","68":"Ancient Apparition","69":"Doom","70":"Ursa","71":"Spirit Breaker","72":"Gyrocopter","73":"Alchemist","74":"Invoker","75":"Silencer","76":"Outworld Devourer","77":"Lycan","78":"Brewmaster","79":"Shadow Demon","80":"Lone Druid","81":"Chaos Knight","82":"Meepo","83":"Treant Protector","84":"Ogre Magi","85":"Undying","86":"Rubick","87":"Disruptor","88":"Nyx Assassin","89":"Naga Siren","90":"Keeper of the Light","91":"Io","92":"Visage","93":"Slark","94":"Medusa","95":"Troll Warlord","96":"Centaur Warrunner","97":"Magnus","98":"Timbersaw","99":"Bristleback","100":"Tusk","101":"Skywrath Mage","102":"Abaddon","103":"Elder Titan","104":"Legion Commander","105":"Techies","106":"Ember Spirit","107":"Earth Spirit","108":"Underlord","109":"Terrorblade","110":"Phoenix","111":"Oracle","112":"Winter Wyvern","113":"Arc Warden","114":"Monkey King","119":"Dark Willow","120":"Pangolier","121":"Grimstroke","123":"Hoodwink","126":"Void Spirit","128":"Snapfire","129":"Mars","135":"Dawnbreaker","136":"Marci","137":"Primal Beast"}');

        // $rd = 1; $dr = 1;

        // // if ($matchdata->picks_bans == null) {
        //     $radiant_hero[1] = $heroname->{$matchdata->players[0]->hero_id};
        //     $radiant_hero[2] = $heroname->{$matchdata->players[1]->hero_id};
        //     $radiant_hero[3] = $heroname->{$matchdata->players[2]->hero_id};
        //     $radiant_hero[4] = $heroname->{$matchdata->players[3]->hero_id};
        //     $radiant_hero[5] = $heroname->{$matchdata->players[4]->hero_id};
        //     $dire_hero[1] = $heroname->{$matchdata->players[5]->hero_id};
        //     $dire_hero[2] = $heroname->{$matchdata->players[6]->hero_id};
        //     $dire_hero[3] = $heroname->{$matchdata->players[7]->hero_id};
        //     $dire_hero[4] = $heroname->{$matchdata->players[8]->hero_id};
        //     $dire_hero[5] = $heroname->{$matchdata->players[9]->hero_id};
        //     var_dump($radiant_hero);
        //     echo "<br>";
        //     echo "<br>";
        //     var_dump($dire_hero);
        //     APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero);

        // // }
        // // foreach ($matchdata->picks_bans as $row) {
        // //     if ($row->is_pick == true) {
        // //         if ($row->team == 0) {
        // //             $radiant_hero[$rd] = $heroname->{$row->hero_id};
        // //             $rd++;
        // //         } else {
        // //             $dire_hero[$dr] = $heroname->{$row->hero_id};
        // //             $dr++;
        // //         }
        // //     }
        // // }
        // // if (APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero)) {
        // //     echo "updated ".$matches->match_id;
        // //     // echo "
        // //     // <script>
        // //     // setInterval(window.location.reload(), 3000);
        // //     // </script>
        // //     // ";
        // }
    }

    public function getHeroesDesc() {
        $matches = APIModel::getMatchWithoutHeroDesc();
        echo "<br><br><br><h2>".$matches->match_id."</h2>";
        $matchdata = json_decode(file_get_contents('https://api.opendota.com/api/matches/'.$matches->match_id));

        $heroname = json_decode('{"1":"Anti-Mage","2":"Axe","3":"Bane","4":"Bloodseeker","5":"Crystal Maiden","6":"Drow Ranger","7":"Earthshaker","8":"Juggernaut","9":"Mirana","10":"Morphling","11":"Shadow Fiend","12":"Phantom Lancer","13":"Puck","14":"Pudge","15":"Razor","16":"Sand King","17":"Storm Spirit","18":"Sven","19":"Tiny","20":"Vengeful Spirit","21":"Windranger","22":"Zeus","23":"Kunkka","25":"Lina","26":"Lion","27":"Shadow Shaman","28":"Slardar","29":"Tidehunter","30":"Witch Doctor","31":"Lich","32":"Riki","33":"Enigma","34":"Tinker","35":"Sniper","36":"Necrophos","37":"Warlock","38":"Beastmaster","39":"Queen of Pain","40":"Venomancer","41":"Faceless Void","42":"Wraith King","43":"Death Prophet","44":"Phantom Assassin","45":"Pugna","46":"Templar Assassin","47":"Viper","48":"Luna","49":"Dragon Knight","50":"Dazzle","51":"Clockwerk","52":"Leshrac","53":"Natures Prophet","54":"Lifestealer","55":"Dark Seer","56":"Clinkz","57":"Omniknight","58":"Enchantress","59":"Huskar","60":"Night Stalker","61":"Broodmother","62":"Bounty Hunter","63":"Weaver","64":"Jakiro","65":"Batrider","66":"Chen","67":"Spectre","68":"Ancient Apparition","69":"Doom","70":"Ursa","71":"Spirit Breaker","72":"Gyrocopter","73":"Alchemist","74":"Invoker","75":"Silencer","76":"Outworld Devourer","77":"Lycan","78":"Brewmaster","79":"Shadow Demon","80":"Lone Druid","81":"Chaos Knight","82":"Meepo","83":"Treant Protector","84":"Ogre Magi","85":"Undying","86":"Rubick","87":"Disruptor","88":"Nyx Assassin","89":"Naga Siren","90":"Keeper of the Light","91":"Io","92":"Visage","93":"Slark","94":"Medusa","95":"Troll Warlord","96":"Centaur Warrunner","97":"Magnus","98":"Timbersaw","99":"Bristleback","100":"Tusk","101":"Skywrath Mage","102":"Abaddon","103":"Elder Titan","104":"Legion Commander","105":"Techies","106":"Ember Spirit","107":"Earth Spirit","108":"Underlord","109":"Terrorblade","110":"Phoenix","111":"Oracle","112":"Winter Wyvern","113":"Arc Warden","114":"Monkey King","119":"Dark Willow","120":"Pangolier","121":"Grimstroke","123":"Hoodwink","126":"Void Spirit","128":"Snapfire","129":"Mars","135":"Dawnbreaker","136":"Marci","137":"Primal Beast"}');

        $rd = 1; $dr = 1;

        // if ($matchdata->picks_bans == null) {
            $radiant_hero[1] = $heroname->{$matchdata->players[0]->hero_id};
            $radiant_hero[2] = $heroname->{$matchdata->players[1]->hero_id};
            $radiant_hero[3] = $heroname->{$matchdata->players[2]->hero_id};
            $radiant_hero[4] = $heroname->{$matchdata->players[3]->hero_id};
            $radiant_hero[5] = $heroname->{$matchdata->players[4]->hero_id};
            $dire_hero[1] = $heroname->{$matchdata->players[5]->hero_id};
            $dire_hero[2] = $heroname->{$matchdata->players[6]->hero_id};
            $dire_hero[3] = $heroname->{$matchdata->players[7]->hero_id};
            $dire_hero[4] = $heroname->{$matchdata->players[8]->hero_id};
            $dire_hero[5] = $heroname->{$matchdata->players[9]->hero_id};
            var_dump($radiant_hero);
            echo "<br>";
            echo "<br>";
            var_dump($dire_hero);
            APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero);
        // }
        // foreach ($matchdata->picks_bans as $row) {
        //     if ($row->is_pick == true) {
        //         if ($row->team == 0) {
        //             $radiant_hero[$rd] = $heroname->{$row->hero_id};
        //             $rd++;
        //         } else {
        //             $dire_hero[$dr] = $heroname->{$row->hero_id};
        //             $dr++;
        //         }
        //     }
        // }
        // if (APIModel::updateHeroName($matches->match_id, $radiant_hero, $dire_hero)) {
        //     echo "updated ".$matches->match_id;
        //     // echo "
        //     // <script>
        //     // setInterval(window.location.reload(), 3000);
        //     // </script>
        //     // ";
        // }
    }

    public function getHeroesName() {
        $data = json_decode(file_get_contents('https://api.opendota.com/api/heroStats'));
        $heroname = [];
        foreach ($data as $row) {
            $heroname[$row->id] = $row->localized_name;
        }
        echo json_encode($heroname);
    }

}
