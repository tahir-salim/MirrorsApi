<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function storePackage(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'services' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

         // Make sure the services exist in the database
        $selectedServices = Service::whereIn('id', $request->services)
                                   ->pluck('id');

        if(count($selectedServices) <= 0) {
            return $this->formatResponse('error', 'no-services-found', null, 400);
        }

        $package = new Package();
        $package->user_id = Auth::id();
        $package->name = $request->name;
        $package->price = $request->price;
        $package->description = $request->description;
        $package->save();

        $package->services()->sync($selectedServices);

        return $this->formatResponse('success', 'package-created', $package);
    }

    public function removePackage($packageID){

        $package = Package::find($packageID);

        if (!$package){
            return $this->formatResponse('error', 'package-not-found', null, 400);
        }

        $talent = User::isNotBlocked()->isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'unauthorized-user', null, 404);
        }

        // relationship in the pivot tables detach
        // it may detach through observer (observer is not working)
        $package->bookingRequest()->detach();
        $package->services()->detach();

        // delete package
        $talent->packages()->where('id', $package->id)->delete();

        return $this->formatResponse('success', 'package-removed-successfully', null);
    }

    public function updatePackage(Request $request, $packageID){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'services' => 'nullable|array'
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $talent = User::isNotBlocked()->isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'unauthorized', null, 404);
        }

        $package = Package::find($packageID);

        if (!$package){
            return $this->formatResponse('error', 'package-not-found', null, 404);
        }

        // Make sure the services exist in the database
        $selectedServices = Service::whereIn('id', $request->services)->pluck('id');

        if(count($selectedServices) <= 0) {
            return $this->formatResponse('error', 'no-services-found', null, 400);
        }

        if ($package->user_id != $talent->id){
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        $package->services()->sync($selectedServices);

        return $this->formatResponse('success', 'update-package', null);
    }
}
