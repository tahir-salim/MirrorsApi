<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function index()
    {
        $serviceCategories = ServiceCategory::with('services')
                                            ->get();
        return $this->formatResponse('success','get-services',$serviceCategories);
    }

    public function storeService(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'service_id' => 'required',
            'price' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $service = Service::find($request->service_id);
        if(!$service) {
            return $this->formatResponse('error', 'service-not-found', null, 404);
        }
        $user = User::find(Auth::id());
        $user->services()->syncWithoutDetaching([
            $service->id => ['price' => $request->price, 'is_active' => 1]
        ]);
        return $this->formatResponse('success', 'service-added');
    }

    public function removeService($serviceId)
    {
        $service = Service::find($serviceId);

        if(!$service) {
            return $this->formatResponse('error', 'service-not-found', null, 404);
        }

        $user = User::find(Auth::id());
        $user->services()->detach($service->id);
        return $this->formatResponse('success', 'service-removed');
    }

    public function updateService(Request $request, $serviceId){

        $validation = Validator::make($request->all(), [
            'price' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $service = Service::find($serviceId);

        if(!$service) {
            return $this->formatResponse('error', 'service-not-found', null, 404);
        }


        $talent = User::isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'unauthorized', null, 404);
        }

        $serviceUser = ServiceUser::where('service_id',$service->id)
            ->where('user_id', $talent->id)
            ->first();

        if (!$serviceUser){
            return $this->formatResponse('error', 'user-service-not-found', null, 404);
        }

        $serviceUser->update([
            'price' => $request->price
        ]);

        return $this->formatResponse('success', 'update-service-price-successfully');
    }
}
