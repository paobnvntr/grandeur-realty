<?php

namespace App\Http\Controllers;

use App\Models\PropertiesFeature;
use App\Models\Property;
use App\Models\HotProperties;
use App\Models\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PropertyController extends Controller
{
    public function properties()
    {
        $properties = Property::where('status', 'available')
            ->where('image', '!=', '[]')
            ->orderBy('created_at', 'DESC')
            ->paginate(4);

        return view('admin.properties.index', compact('properties'));
    }

    public function addProperties()
    {
        return view('admin.properties.addProperties');
    }

    public function validateAddPropertiesForm(Request $request)
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

    public function saveProperties(Request $request)
    {
        $image = [];
        if ($request->hasFile('image')) {
            // Create a folder name using the current timestamp and the trimmed request name
            $folderName = time() . '_' . trim($request->name);
            $propertyFolder = public_path("uploads/property/{$folderName}");

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

        $propertyData = [
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

        $createProperty = Property::create($propertyData);

        if ($createProperty) {
            $this->logPropertyAddSuccess($createProperty->id, Auth::user()->username);

            return redirect()->route('properties.List')->with('success', 'Property added successfully!');
        } else {
            $this->logPropertyAddFailed(Auth::user()->username);

            return redirect()->route('properties.List')->with('error', 'Failed to add property!');
        }
    }

    private function logPropertyAddSuccess($propertyId, $username)
    {
        Log::create([
            'type' => 'Add Property',
            'user' => $username,
            'subject' => 'Add Property Success',
            'message' => "$username has successfully added a new property with ID: $propertyId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logPropertyAddFailed($username)
    {
        Log::create([
            'type' => 'Add Property',
            'user' => $username,
            'subject' => 'Add Property Failed',
            'message' => "$username failed to add a new property.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function soldProperties()
    {
        $properties = Property::where('status', 'sold')
            ->where('image', '!=', '[]')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        return view('admin.properties.soldProperties', compact('properties'));
    }

    public function markAsSold($id)
    {
        try {
            $property = Property::findOrFail($id);

            $property->update([
                'status' => 'sold',
                'date_sold' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila')
            ]);

            // Log success
            $this->logPropertySoldSuccess($id);

            return redirect()->route('properties.List')->with('success', 'Property marked as sold successfully');
        } catch (\Exception $e) {
            // Log failure
            $this->logPropertySoldFailed($id, $e->getMessage());

            return redirect()->route('properties.List')->with('error', 'Failed to mark property as sold.');
        }
    }

    public function logPropertySoldSuccess($propertyId)
    {
        Log::create([
            'type' => 'success',
            'user' => auth()->user()->username,
            'subject' => 'Property Sold',
            'message' => "Property with ID {$propertyId} was marked as sold.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila')
        ]);
    }

    public function logPropertySoldFailed($propertyId, $errorMessage)
    {
        Log::create([
            'type' => 'error',
            'user' => auth()->user()->username,
            'subject' => 'Property Sale Failed',
            'message' => "Failed to mark property with ID {$propertyId} as sold. Error: {$errorMessage}",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila')
        ]);
    }

    public function markAsAvailable($id)
    {
        try {
            $property = Property::findOrFail($id);

            $property->update([
                'status' => 'available',
                'date_sold' => null,
                'updated_at' => now('Asia/Manila')
            ]);

            // Log success
            $this->logPropertyAvailableSuccess($id);

            return redirect()->route('properties.List')->with('success', 'Property marked as available successfully');
        } catch (\Exception $e) {
            // Log failure
            $this->logPropertyAvailableFailed($id, $e->getMessage());

            return redirect()->route('properties.List')->with('error', 'Failed to mark property as available.');
        }
    }

    public function logPropertyAvailableSuccess($propertyId)
    {
        Log::create([
            'type' => 'success',
            'user' => auth()->user()->username,
            'subject' => 'Property Available',
            'message' => "Property with ID {$propertyId} was marked as available.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila')
        ]);
    }

    public function logPropertyAvailableFailed($propertyId, $errorMessage)
    {
        Log::create([
            'type' => 'error',
            'user' => auth()->user()->username,
            'subject' => 'Property Availability Failed',
            'message' => "Failed to mark property with ID {$propertyId} as available. Error: {$errorMessage}",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila')
        ]);
    }

    public function delete($id)
    {
        try {
            // Attempt to find the property by ID
            $property = Property::findOrFail($id);

            // Get the folder name where the images are stored (if applicable)
            $folderName = $property->folder_name;
            $propertyFolder = public_path("uploads/property/{$folderName}");

            // Check if the folder exists and delete all images and the folder
            if (is_dir($propertyFolder)) {
                // Get all the files in the directory
                $files = glob($propertyFolder . '/*');

                // Loop through each file and delete it
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file); // Delete the file
                    }
                }

                // Remove the directory after deleting the files
                rmdir($propertyFolder);
            }

            // Delete the property from the database
            $property->delete();

            // Log the success
            $this->logPropertyDeletionSuccess(auth()->user()->username, $property->id);

            // Redirect back with a success message
            return redirect()->route('properties.List')->with('success', 'Property and images deleted successfully');
        } catch (ModelNotFoundException $e) {
            // Log the failure if the property is not found
            $this->logPropertyDeletionFailed(auth()->user()->username, $id);

            // Redirect back with an error message
            return redirect()->route('properties.List')->with('error', 'Property not found');
        } catch (\Exception $e) {
            // Redirect back with a generic error message
            return redirect()->route('properties.List')->with('error', 'An error occurred while deleting the property');
        }
    }

    // Log for successful property deletion
    private function logPropertyDeletionSuccess($userName, $propertyId)
    {
        Log::create([
            'type' => 'Property Deletion',
            'user' => $userName,
            'subject' => 'Property Deletion Success',
            'message' => "$userName successfully deleted property ID: $propertyId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    // Log for failed property deletion
    private function logPropertyDeletionFailed($userName, $propertyId)
    {
        Log::create([
            'type' => 'Property Deletion',
            'user' => $userName,
            'subject' => 'Property Deletion Failed',
            'message' => "$userName attempted to delete a non-existing property ID: $propertyId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function settings()
    {
        return view('admin.properties.settingsProperties');
    }

    public function editHotProperties()
    {
        $defaultImages = [
            '../images/hero_bg_3.jpg',
            '../images/hero_bg_2.jpg',
            '../images/hero_bg_1.jpg'
        ];

        $cities = HotProperties::orderBy('priority', 'asc')->limit(6)->get();

        if ($cities->isNotEmpty()) {
            foreach ($cities as $city) {
                $city->image_url = $city->image
                    ? asset('uploads/hot_properties/' . $city->image)
                    : $defaultImages[array_rand($defaultImages)];
            }
        }

        $totalSlots = 6;
        $occupiedSlots = HotProperties::pluck('priority')->toArray();

        $availableSlots = [];
        for ($i = 1; $i <= $totalSlots; $i++) {
            if (!in_array($i, $occupiedSlots)) {
                $availableSlots[] = $i;
            }
        }

        return view('admin.properties.imagesHotProperties', compact('cities', 'availableSlots'));
    }

    public function validateAddHotPropertiesForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'slot' => 'required|numeric',
            'city' => 'required|unique:hot_properties,city',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveAddHotProperties(Request $request)
    {
        $city = trim($request->city);

        // Check if the city already exists
        $existingCity = HotProperties::where('city', $city)->first();

        if ($existingCity) {
            return redirect()->route('properties.editHotProperties')->with('error', 'City already exists!');
        }

        $addHotProperties = HotProperties::create([
            'city' => $city,
            'image' => $this->uploadAddHotPropertiesImage($request, $city),
            'priority' => $request->slot,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        if ($addHotProperties) {
            $this->logAddHotProperties($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('success', 'Hot Properties Added Successfully!');
        } else {
            $this->logAddHotPropertiesFailed($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('error', 'Failed to add hot properties!');
        }
    }

    private function uploadAddHotPropertiesImage($request, $city)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a filename using the city name and current timestamp
            $fileName = strtolower(str_replace([' ', ','], '_', $city)) . '_' . time() . '.' . $image->getClientOriginalExtension(); // Create the new filename

            $image->move(public_path('uploads/hot_properties'), $fileName); // Move the file to the unique folder

            return $fileName;
        }

        return null;
    }

    private function logAddHotProperties($city, $username)
    {
        Log::create([
            'type' => 'Add Hot Properties',
            'user' => $username,
            'subject' => 'Add Hot Properties Success',
            'message' => "$username has successfully added the hot properties for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logAddHotPropertiesFailed($city, $username)
    {
        Log::create([
            'type' => 'Add Hot Properties',
            'user' => $username,
            'subject' => 'Add Hot Properties Failed',
            'message' => "$username failed to add the hot properties for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function validateEditHotPropertiesImagesForm(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                '_token' => 'required',
                'city' => 'required',
                'image_edit' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'image_edit.required' => 'Please upload an image.',
                'image_edit.image' => 'The uploaded file must be an image.',
                'image_edit.mimes' => 'The uploaded image must be a type of: jpeg, png, jpg',
                'image_edit.max' => 'The uploaded image must not be greater than 2MB.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveEditHotPropertiesImages(Request $request, $city)
    {
        $hotPropertiesImage = HotProperties::where('city', $city)->first();

        if (!$hotPropertiesImage) {
            return redirect()->route('properties.editHotProperties')->with('error', 'City not found!');
        }

        $this->deleteExistingImage($hotPropertiesImage->image);

        $editHotProperties = $hotPropertiesImage->update([
            'image' => $this->uploadHotPropertiesImage($request, $city),
            'updated_at' => now('Asia/Manila'),
        ]);

        if ($editHotProperties) {
            $this->logEditHotProperties($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('success', 'Hot Properties Image Updated Successfully!');
        } else {
            $this->logEditHotPropertiesFailed($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('error', 'Failed to update hot properties image!');
        }
    }

    private function deleteExistingImage($image)
    {
        if (!$image) {
            return;
        }

        $imagePath = public_path('uploads/hot_properties/' . $image);

        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }

    private function uploadHotPropertiesImage($request, $city)
    {
        $image = $request->file('image_edit');

        $fileName = strtolower(str_replace([' ', ','], '_', $city)) . '_' . time() . '.' . $image->getClientOriginalExtension(); // Create the new filename

        $image->move(public_path('uploads/hot_properties'), $fileName); // Move the file to the unique folder

        return $fileName;
    }

    private function logEditHotProperties($city, $username)
    {
        Log::create([
            'type' => 'Edit Hot Properties',
            'user' => $username,
            'subject' => 'Edit Hot Properties Success',
            'message' => "$username has successfully updated the hot properties image for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logEditHotPropertiesFailed($city, $username)
    {
        Log::create([
            'type' => 'Edit Hot Properties',
            'user' => $username,
            'subject' => 'Edit Hot Properties Failed',
            'message' => "$username failed to update the hot properties image for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function deleteHotProperties(string $id)
    {
        $hotProperties = HotProperties::findOrFail($id);
        $city = $hotProperties->city;

        if (!$hotProperties) {
            return redirect()->route('properties.editHotProperties')->with('error', 'City not found!');
        }

        $this->deleteExistingImage($hotProperties->image);

        $deleteHotProperties = $hotProperties->delete();

        if ($deleteHotProperties) {
            $this->logDeleteHotProperties($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('success', 'Hot Properties Deleted Successfully!');
        } else {
            $this->logDeleteHotPropertiesFailed($city, Auth::user()->username);

            return redirect()->route('properties.editHotProperties')->with('error', 'Failed to delete hot properties!');
        }
    }

    private function logDeleteHotProperties($city, $username)
    {
        Log::create([
            'type' => 'Delete Hot Properties',
            'user' => $username,
            'subject' => 'Delete Hot Properties Success',
            'message' => "$username has successfully deleted the hot properties for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logDeleteHotPropertiesFailed($city, $username)
    {
        Log::create([
            'type' => 'Delete Hot Properties',
            'user' => $username,
            'subject' => 'Delete Hot Properties Failed',
            'message' => "$username failed to delete the hot properties for $city.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function editPropertiesFeatures()
    {
        $features = PropertiesFeature::all();
        $icons = [
            'flaticon-house',
            'flaticon-building',
            'flaticon-house-2',
            'flaticon-house-1',
            'flaticon-house-3',
            'flaticon-house-4',
            'flaticon-house-5',
        ];

        return view('admin.properties.featureProperties', compact('features', 'icons'));
    }

    public function validateEditPropertiesFeaturesForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'icon' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveEditPropertiesFeatures(Request $request, $id)
    {
        $feature = PropertiesFeature::findOrFail($id);

        if ($this->shouldUpdateFeatureDetails($feature, $request)) {
            $this->updateFeatureDetails($feature, $request);

            $this->logUpdateFeature($feature, Auth::user()->username);

            return redirect()->route('properties.editPropertiesFeatures')->with('success', 'Feature Updated Successfully!');
        } else {
            return redirect()->route('properties.editPropertiesFeatures')->with('failed', 'Please Edit a Field!');
        }
    }

    private function shouldUpdateFeatureDetails($feature, Request $request)
    {
        return (
            $request->input('icon') !== $feature->icon ||
            $request->input('title') !== $feature->title ||
            $request->input('description') !== $feature->description
        );
    }

    private function updateFeatureDetails($feature, Request $request)
    {
        $feature->icon = trim($request->icon);
        $feature->title = trim($request->title);
        $feature->description = trim($request->description);
        $feature->updated_at = now('Asia/Manila');
        $feature->save();
    }

    private function logUpdateFeature($feature, $username)
    {
        $logType = 'Edit Property Feature';
        $logUser = $username;

        if ($feature->wasChanged()) {
            $logSubject = 'Edit Property Feature Success';
            $logMessage = "$logUser has successfully updated the feature: $feature->title.";
        } else {
            $logSubject = 'Edit Property Feature Failed';
            $logMessage = "$logUser attempted to update the feature: $feature->title, but no changes were made.";
        }

        Log::create([
            'type' => $logType,
            'user' => $logUser,
            'subject' => $logSubject,
            'message' => $logMessage,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function validatePropertiesUpdateForm(Request $request, $id)
    {
        $properties = Property::findOrFail($id);

        if (
            trim(strtolower($request->user_type)) === trim(strtolower($properties->user_type)) &&
            $request->name === $properties->name &&
            $request->cellphone_number === $properties->cellphone_number &&
            $request->email === $properties->email &&
            trim(strtolower($request->property_type)) === trim(strtolower($properties->property_type)) &&
            $request->city === $properties->city &&
            $request->address === $properties->address &&
            $request->size === $properties->size &&
            trim(strtolower($request->property_status)) === trim(strtolower($properties->property_status)) &&
            $request->price === $properties->price &&
            $request->bedrooms === $properties->bedrooms &&
            $request->bathrooms === $properties->bathrooms &&
            $request->garage === $properties->garage &&
            $request->description === $properties->description
        ) {
            return response()->json(['message' => 'No changes detected']);
        } else {
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
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
            } else {
                return response()->json(['message' => 'Validation passed']);
            }
        }
    }

    public function updateProperties(Request $request, $id)
    {
        $properties = Property::findOrFail($id);

        $properties->update([
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
            'updated_at' => now('Asia/Manila'),
        ]);

        $user = Auth::user()->username;

        if ($properties->save()) {
            $this->logUpdatePropertiesSuccess($user, $properties->id);

            session()->flash('success', 'Property updated successfully!');
            return response()->json(['message' => 'Property updated successfully.']);
        } else {
            $this->logUpdatePropertiesFailed($user, $properties->id);

            return response()->json(['message' => 'Failed to update property.']);
        }
    }

    private function logUpdatePropertiesSuccess($user, $propertyId)
    {
        Log::create([
            'type' => 'Update Properties',
            'user' => $user,
            'subject' => 'Update Properties Details Success',
            'message' => "$user has successfully updated the property with ID $propertyId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    private function logUpdatePropertiesFailed($user, $propertyId)
    {
        Log::create([
            'type' => 'Update Properties',
            'user' => $user,
            'subject' => 'Update Properties Details Failed',
            'message' => "$user failed to update the property with ID $propertyId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
}
