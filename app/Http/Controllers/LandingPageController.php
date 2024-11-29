<?php

namespace App\Http\Controllers;

use App\Models\Inquiries;
use App\Models\ListWithUs;
use App\Models\PropertiesFeature;
use App\Models\Property;
use App\Models\User;
use App\Models\HotProperties;
use App\Notifications\NewListWithUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactUs;

class LandingPageController extends Controller
{
    public function home()
    {
        $defaultImages = [
            '../images/hero_bg_3.jpg',
            '../images/hero_bg_2.jpg',
            '../images/hero_bg_1.jpg'
        ];

        $cities = HotProperties::orderBy('priority', 'asc')->limit(6)->get();

        if ($cities->isEmpty()) {
            $cities = Property::select('city')
                ->where('status', 'available')
                ->where('image', '!=', '[]')
                ->selectRaw('count(*) as property_count')
                ->groupBy('city')
                ->orderBy('property_count', 'desc')
                ->limit(6)
                ->get();

            foreach ($cities as $city) {
                $city->image_url = $defaultImages[array_rand($defaultImages)];
            }
        } else {
            foreach ($cities as $city) {
                $city->image_url = $city->image
                    ? asset('uploads/hot_properties/' . $city->image)
                    : $defaultImages[array_rand($defaultImages)];
            }
        }

        $features = PropertiesFeature::all();

        return view('landing-page.home', compact('cities', 'features'));
    }

    public function allProperties(Request $request)
    {
        // Get all properties without pagination
        $allProperties = Property::where('status', 'available')
            ->where('image', '!=', '[]')
            ->orderBy('created_at', 'desc')
            ->get();

        $defaultImages = [
            '../images/hero_bg_3.jpg',
            '../images/hero_bg_2.jpg',
            '../images/hero_bg_1.jpg'
        ];

        $cities = HotProperties::orderBy('priority', 'asc')->limit(6)->get();

        if ($cities->isEmpty()) {
            $cities = Property::select('city')
                ->where('status', 'available')
                ->where('image', '!=', '[]')
                ->selectRaw('count(*) as property_count')
                ->groupBy('city')
                ->orderBy('property_count', 'desc')
                ->limit(6)
                ->get();

            foreach ($cities as $city) {
                $city->image_url = $defaultImages[array_rand($defaultImages)];
            }
        } else {
            foreach ($cities as $city) {
                $city->image_url = $city->image
                    ? asset('uploads/hot_properties/' . $city->image)
                    : $defaultImages[array_rand($defaultImages)];
            }
        }

        return view('landing-page.properties.allProperties', compact('allProperties', 'cities'));
    }

    public function hotProperties($city)
    {
        $hotProperties = Property::where('city', $city)
            ->where('status', 'available')
            ->where('image', '!=', '[]')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landing-page.properties.hotProperties', compact('hotProperties', 'city'));
    }

    public function propertyDetails($id)
    {
        // Attempt to find the property in the list_with_us table
        $property = Property::findOrFail($id);

        // Decode the image JSON
        $property->image = json_decode($property->image, true);

        // Ensure the analytics relationship is correctly defined in your Property model
        $analytics = $property->analytics()->first(); // Assuming 'analytics' relationship is defined

        if ($analytics) {
            // Increment existing views
            $analytics->increment('views');
        } else {
            // Check if the listing actually exists in the list_with_us table
            $listing = Property::find($id); // Ensure the model matches your table

            if ($listing) {
                // Create analytics record with initial view count
                $listing->analytics()->create(['views' => 1]);
            } else {
                // Handle case where listing does not exist
                return redirect()->route('propertyDetails', $id)->with('error', 'Property listing does not exist.');
            }
        }

        return view('landing-page.properties.detailsProperties', compact('property'));
    }


    public function validateSendInquiryForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'property_name' => 'required',
            'name' => 'required',
            'cellphone_number' => 'required|regex:/^09[0-9]{9}$/i|numeric',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'subject' => 'required',
            'message' => 'required',
            'termsCheckbox' => 'required',
        ], [
            'termsCheckbox.required' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveInquiry(Request $request, $id)
    {
        $inquiryData = [
            'property_name' => trim($request->property_name),
            'name' => trim($request->name),
            'cellphone_number' => trim($request->cellphone_number),
            'email' => trim($request->email),
            'subject' => trim($request->subject),
            'message' => trim($request->message),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createInquiry = Inquiries::create($inquiryData);

        $analytics = Property::findOrFail($id)->analytics()->first();
        if ($analytics) {
            $analytics->increment('interactions');
        } else {
            $analyticsData = ['views' => 0, 'interactions' => 1];
            Property::findOrFail($id)->analytics()->create($analyticsData);
        }

        if ($createInquiry) {
            return redirect()->route('propertyDetails', $id)->with('success', 'Inquiry submitted successfully!');
        } else {
            return redirect()->route('propertyDetails', $id)->with('error', 'Failed to submit inquiry!');
        }
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
            'cellphone_number' => 'required|regex:/^09[0-9]{9}$/i|numeric',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'property_type' => 'required',
            'city' => 'required',
            'address' => 'required',
            'size' => 'required|numeric',
            'property_status' => 'required',
            'price' => 'required|numeric',
            'bedrooms' => 'numeric',
            'bathrooms' => 'numeric',
            'garage' => 'numeric',

            'image' => 'required|array',
            'image.*' => 'image|mimes:jpeg,png,jpg|max:2048',

            'termsCheckbox' => 'required',
        ], [
            'image.required' => 'Please upload at least one image.',
            'image.*.image' => 'The uploaded file must be an image.',
            'image.*.mimes' => 'The uploaded image must be a type of: jpeg, png, jpg',
            'image.*.max' => 'The uploaded image must not be greater than 2MB.',
            'termsCheckbox.required' => '',
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
            'bedrooms' => trim($request->bedrooms) ?: 0,
            'bathrooms' => trim($request->bathrooms) ?: 0,
            'garage' => trim($request->garage) ?: 0,
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
            'cellphone_number' => 'required|regex:/^09[0-9]{9}$/i|numeric',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'subject' => 'required',
            'message' => 'required',
            'termsCheckbox' => 'required',
        ], [
            'termsCheckbox.required' => '',
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
            'cellphone_number' => trim($request->cellphone_number),
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
