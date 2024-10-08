<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function properties()
    {
        $properties = Property::where('status', 'available')
            ->orderBy('created_at', 'ASC')
            ->paginate(5);
        return view('admin.properties.index', compact('properties'));
    }

    public function soldProperties()
    {
        $properties = Property::where('status', 'sold')
            ->orderBy('created_at', 'ASC')
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
}
