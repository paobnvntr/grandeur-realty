<?php

namespace App\Http\Controllers;

use App\Models\ListWithUs;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Property;
use Illuminate\Http\Request;

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

}
