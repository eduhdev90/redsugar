<?php

namespace App\Http\Controllers\Api;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    // Retorna todos os anúncios
    public function index()
    {
        $ads = Ads::all();
        return $this->enrichAdsWithProfileData($ads);
    }

    // Retorna um anúncio específico pelo ID
    public function showById($id)
    {
        $ad = Ads::with('user', 'user.profile')->find($id);
        if (!$ad) return response()->json(['message' => 'Anúncio não encontrado'], 404);

        $ad->profile_image = !empty($ad->user->profile->profile_image) ? Storage::url($ad->user->profile->profile_image) : '';
        $ad->user->profile->age = $this->calcAge($ad->user->profile->birthday);

        return response()->json($ad);
    }

     public function store(Request $request)
     {
         return Ads::create($request->all());
     }

     public function update(Request $request, Ads $ads)
     {
         $ads->update($request->all());
         return $ads;
     }

     public function destroy(Ads $ads, $id)
     {
         $ads->find($id)->delete();
         return response()->json(['message' => 'Anúncio excluído com sucesso']);
     }

    public function activeWithinLast48Hours()
    {
        $ads = Ads::where('active', true)
                  ->where('created_at', '>=', Carbon::now()->subDays(2))
                  ->with('user', 'user.profile')
                  ->get();
        return $this->enrichAdsWithProfileData($ads);
    }

    public function showByUserId($id)
    {
        $ads = Ads::where('user_id', $id)
                  ->with('user', 'user.profile')
                  ->get();
        return $this->enrichAdsWithProfileData($ads);
    }

    public function showByUserActiveId($id)
    {
        $ads = Ads::where('user_id', $id)
                  ->where('created_at', '>=', Carbon::now()->subDays(2))
                  ->with('user', 'user.profile')
                  ->get();
        return $this->enrichAdsWithProfileData($ads);
    }

    public function getByLocale($locale)
    {
        $ads = Ads::where('active', true)
                  ->where('created_at', '>=', Carbon::now()->subDays(2))
                  ->where(function ($query) use ($locale) {
                      $query->where('city', 'like', "%$locale%")
                            ->orWhere('state', 'like', "%$locale%")
                            ->orWhere('country', 'like', "%$locale%");
                  })
                  ->with('user', 'user.profile')
                  ->get();
        return $this->enrichAdsWithProfileData($ads);
    }

    public function getByDistance(Request $request, $km)
    {
        $ads = Ads::where('active', true)
                  ->where('created_at', '>=', Carbon::now()->subDays(2))
                  ->with('user', 'user.profile')
                  ->get();
        $filteredAds = $ads->filter(function ($ad) use ($request, $km) {
            return $this->calculateDistance($ad->latitude, $ad->longitude, $request->lat, $request->long) < $km;
        });
        return $this->enrichAdsWithProfileData($filteredAds);
    }

    private function enrichAdsWithProfileData($ads)
    {
        return $ads->map(function ($ad) {
            if($ad->user->profile != null){
                $ad->profile_image = !empty($ad->user->profile->profile_image) ? Storage::url($ad->user->profile->profile_image) : '';
                $ad->user->profile->age = $this->calcAge($ad->user->profile->birthday);
            }
            return $ad;
        });
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

    private function calcAge($birthday)
    {
        return \DateTime::createFromFormat('Y-m-d', $birthday)->diff(new \DateTime('now'))->y;
    }
}






