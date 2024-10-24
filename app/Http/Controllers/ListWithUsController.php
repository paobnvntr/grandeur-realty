<?php

namespace App\Http\Controllers;

use App\Models\ListWithUs;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListWithUsController extends Controller
{
    public function listWithUs()
    {
        $listings = ListWithUs::orderBy('created_at', 'ASC')
            ->paginate(5);
        return view('admin.listWithUs.index', compact('listings'));
    }

    public function approveList($id)
    {
        $listing = ListWithUs::findOrFail($id);

        $propertyData = [
            'user_type' => $listing->user_type,
            'name' => $listing->name,
            'cellphone_number' => $listing->cellphone_number,
            'email' => $listing->email,
            'property_type' => $listing->property_type,
            'city' => $listing->city,
            'address' => $listing->address,
            'size' => $listing->size,
            'property_status' => $listing->property_status,
            'price' => $listing->price,
            'bedrooms' => $listing->bedrooms,
            'bathrooms' => $listing->bathrooms,
            'garage' => $listing->garage,
            'description' => $listing->description,
            'folder_name' => $listing->folder_name,
            'image' => $listing->image,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        try {
            $oldFolderPath = public_path("uploads/list-with-us/{$listing->folder_name}");
            $newFolderPath = public_path("uploads/property/{$listing->folder_name}");

            if (!file_exists($newFolderPath)) {
                mkdir($newFolderPath, 0755, true);
            }

            $images = json_decode($listing->image);
            foreach ($images as $image) {
                $oldImagePath = "{$oldFolderPath}/{$image}";
                $newImagePath = "{$newFolderPath}/{$image}";

                if (file_exists($oldImagePath)) {
                    copy($oldImagePath, $newImagePath);
                }
            }

            Property::create($propertyData);

            $this->logApprovalSuccess(Auth::user()->username);

            if (is_dir($oldFolderPath)) {
                $files = glob($oldFolderPath . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($oldFolderPath);
            }

            $listing->delete();

            return redirect()->route('listWithUs')->with('success', 'Listing approved and moved to Properties successfully.');

        } catch (\Exception $e) {
            $this->logApprovalFailed(Auth::user()->username, $listing->name);

            return redirect()->route('listWithUs')->with('error', 'Failed to approve and move listing.');
        }
    }

    private function logApprovalSuccess($user)
    {
        Log::create([
            'type' => 'Approve Listing',
            'user' => $user,
            'subject' => 'Approve Listing Success',
            'message' => "$user has successfully approved and moved to Properties.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logApprovalFailed($user, $listingId)
    {
        Log::create([
            'type' => 'Approve Listing',
            'user' => $user,
            'subject' => 'Approve Listing Failed',
            'message' => "$user attempted to approve listing ID $listingId, but the operation failed.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function disapproveList($id)
    {
        $listing = ListWithUs::findOrFail($id);

        $user = Auth::user()->username;

        try {
            // Get the folder name where the images are stored
            $folderName = $listing->folder_name;
            $propertyFolder = public_path("uploads/list-with-us/{$folderName}");

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

            // Delete the listing from the database
            $listing->delete();

            // Log the success of disapproval and deletion
            $this->logDisapproveSuccess($user, $listing->id);

            // Redirect back with success message
            return redirect()->route('listWithUs')->with('success', 'Listing Disapproved and Deleted Successfully.');
        } catch (\Exception $e) {
            // Log the failure of disapproval and deletion
            $this->logDisapproveFailed($user, $listing->id);

            // Optionally, handle exceptions (e.g., display error to user)
            return redirect()->route('listWithUs')->with('error', 'Failed to disapprove and delete the listing.');
        }
    }

    // Log function for successful disapproval
    private function logDisapproveSuccess($user, $listingId)
    {
        Log::create([
            'type' => 'Disapprove Listing',
            'user' => $user,
            'subject' => 'Disapprove Listing Success',
            'message' => "$user has successfully disapproved and deleted the listing with ID $listingId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    // Log function for failed disapproval
    private function logDisapproveFailed($user, $listingId)
    {
        Log::create([
            'type' => 'Disapprove Listing',
            'user' => $user,
            'subject' => 'Disapprove Listing Failed',
            'message' => "$user failed to disapprove and delete the listing with ID $listingId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function validateListUpdateForm(Request $request, $id)
    {
        $listing = ListWithUs::findOrFail($id);

        if (
            trim(strtolower($request->user_type)) === trim(strtolower($listing->user_type)) &&
            $request->name === $listing->name &&
            $request->cellphone_number === $listing->cellphone_number &&
            $request->email === $listing->email &&
            trim(strtolower($request->property_type)) === trim(strtolower($listing->property_type)) &&
            $request->city === $listing->city &&
            $request->address === $listing->address &&
            $request->size === $listing->size &&
            trim(strtolower($request->property_status)) === trim(strtolower($listing->property_status)) &&
            $request->price === $listing->price &&
            $request->bedrooms === $listing->bedrooms &&
            $request->bathrooms === $listing->bathrooms &&
            $request->garage === $listing->garage &&
            $request->description === $listing->description
        ) {
            return response()->json(['message' => 'No changes detected']);
        } else {
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
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
            } else {
                return response()->json(['message' => 'Validation passed']);
            }
        }
    }

    public function updateList(Request $request, $id)
    {
        // Find the listing by ID
        $listing = ListWithUs::findOrFail($id);

        // Only update the fields related to details (excluding images)
        $listing->update([
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
            'updated_at' => now('Asia/Manila'), // Update the timestamp
        ]);

        $user = Auth::user()->username;

        if ($listing->save()) {
            $this->logUpdateListSuccess($user, $listing->id);

            session()->flash('success', 'Listing updated successfully!');
            return response()->json(['message' => 'Listing updated successfully.']);
        } else {
            $this->logUpdateListFailed($user, $listing->id);

            return response()->json(['message' => 'Failed to update listing.']);
        }
    }

    private function logUpdateListSuccess($user, $listingId)
    {
        Log::create([
            'type' => 'Update Listing',
            'user' => $user,
            'subject' => 'Update Listing Details Success',
            'message' => "$user has successfully updated the listing with ID $listingId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
    private function logUpdateListFailed($user, $listingId)
    {
        Log::create([
            'type' => 'Update Listing',
            'user' => $user,
            'subject' => 'Update Listing Details Failed',
            'message' => "$user failed to update the listing with ID $listingId.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
}
