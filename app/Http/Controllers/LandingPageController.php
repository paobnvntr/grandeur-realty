<?php

namespace App\Http\Controllers;

use App\Models\ListWithUs;
use App\Models\Property;
use App\Models\User;
use App\Notifications\NewListWithUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactUs;

class LandingPageController extends Controller
{
    public function home()
    {
        $cities = Property::select('city')
            ->where('status', 'available')
            ->selectRaw('count(*) as property_count')
            ->groupBy('city')
            ->orderBy('property_count', 'desc')
            ->limit(6)
            ->get();

        return view('landing-page.home', compact('cities'));
    }

    public function allProperties(Request $request)
    {
        // Get all properties without pagination
        $allProperties = Property::where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->get();

        $hotCities = Property::select('city')
            ->where('status', 'available')
            ->selectRaw('count(*) as property_count')
            ->groupBy('city')
            ->orderBy('property_count', 'desc')
            ->limit(6)
            ->get();

        return view('landing-page.properties.allProperties', compact('allProperties', 'hotCities'));
    }

    public function hotProperties($city)
    {
        $hotProperties = Property::where('city', $city)
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('landing-page.properties.hotProperties', compact('hotProperties', 'city'));
    }
    
    public function propertyDetails($id)
    {
        $property = Property::findOrFail($id);
        $property->image = json_decode($property->image, true);
    
        return view('landing-page.properties.detailsProperties', compact('property'));
    }

    public function listWithUsForm()
    {
        return view('landing-page.listWithUs');
    }

    public function validateListWithUsForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'user_type' => 'required',
            'name' => 'required',
            'cellphone_number' => 'required|regex:/^09[0-9]{9}$/i',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'property_type' => 'required',
            'city' => 'required',
            'address' => 'required',
            'size' => 'required',
            'property_status' => 'required',
            'price' => 'required',

            'image' => 'required|array',
            'image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'image.required' => 'Please upload at least one image.',
            'image.*.image' => 'The uploaded file must be an image.',
            'image.*.mimes' => 'The uploaded image must be a type of: jpeg, png, jpg',
            'image.*.max' => 'The uploaded image must not be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveListWithUs(Request $request)
    {
        $image = [];
        if ($request->hasFile('image')) {
            // Create a folder name using the current timestamp and the trimmed request name
            $folderName = time() . '_' . trim($request->name);
            $propertyFolder = public_path("uploads/list-with-us/{$folderName}");

            // Create the new directory
            mkdir($propertyFolder, 0755, true);

            foreach ($request->file('image') as $file) {
                $originalName = $file->getClientOriginalName();
                $safeFileName = str_replace([' ', ','], '_', $originalName);

                $fileName = time() . '_' . $safeFileName; // Create the new filename
                $file->move($propertyFolder, $fileName); // Move the file to the unique folder
                $image[] = $fileName;
            }
        } else {
            \Log::info('No files received.');
        }

        $listWithUsData = [
            'user_type' => trim($request->user_type),
            'name' => trim($request->name),
            'cellphone_number' => trim($request->cellphone_number),
            'email' => trim($request->email),
            'property_type' => trim($request->property_type),
            'city' => trim($request->city),
            'address' => trim($request->address),
            'size' => trim($request->size),
            'property_status' => trim($request->property_status),
            'price' => trim($request->price),
            'bedrooms' => trim($request->bedrooms),
            'bathrooms' => trim($request->bathrooms),
            'garage' => trim($request->garage),
            'description' => trim($request->description),
            'folder_name' => $folderName,
            'image' => json_encode($image),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createListWithUs = ListWithUs::create($listWithUsData);

        if ($createListWithUs) {
            $users = User::get();

            $notificationData = [
                'id' => $createListWithUs->id,
                'name' => $request->name,
            ];

            foreach ($users as $user) {
                $user->notify(new NewListWithUs($notificationData));
            }

            return redirect()->route('listWithUsForm')->with('success', 'Property listed successfully!');
        } else {
            return redirect()->route('listWithUsForm')->with('error', 'Failed to list property!');
        }
    }

    public function contactUsForm()
    {
        return view('landing-page.contact');
    }

    public function validateContactUsForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveContactUs(Request $request)
    {
        $contactUsData = [
            'name' => trim($request->name),
            'email' => trim($request->email),
            'subject' => trim($request->subject),
            'message' => trim($request->message),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createContactUs = ContactUs::create($contactUsData);

        if ($createContactUs) {
            return redirect()->route('contactUsForm')->with('success', 'Message sent successfully!');
        } else {
            return redirect()->route('contactUsForm')->with('error', 'Failed to send message!');
        }
    }

    public function privacyPolicy()
    {
        return view('landing-page.privacyPolicy');
    }
}
