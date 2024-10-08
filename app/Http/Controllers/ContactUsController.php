<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactUsController extends Controller
{
    public function contactUs()
    {
        $contacts = ContactUs::orderBy('created_at', 'ASC')
            ->paginate(5);
        return view('admin.contact.index', compact('contacts'));
    }

    public function deleteContactUs(string $id)
    {
        try {
            DB::beginTransaction();

            $contact = ContactUs::findOrFail($id);
            $user = Auth::user()->username;

            $this->createContactUsDeleteLog($user, $contact);

            $contact->delete();

            DB::commit();

            return redirect()->route('contactUs')->with('success', 'Contact Us Inquiry Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->createContactUsDeleteLog($user, $contact, 'Failed to delete Contact Us Inquiry', $e->getMessage());

            return redirect()->route('contactUs')->with('failed', 'Failed to delete Contact Us Inquiry!');
        }
    }

    private function createContactUsDeleteLog($user, $contact, $subject = 'Delete Contact Us Inquiry Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Contact Us Inquiry',
            'user' => $user,
            'subject' => $subject,
            'message' => "Contact Us inquiry from $contact->name has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }

        Log::create($logData);
    }
}
