<?php

namespace App\Http\Controllers;

use App\Models\Inquiries;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InquiriesController extends Controller
{
    public function inquiries()
    {
        $inquiries = Inquiries::orderBy('created_at', 'DESC')->paginate(5);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function deleteInquiry(string $id)
    {
        try {
            DB::beginTransaction();

            $inquiry = Inquiries::findOrFail($id);
            $user = Auth::user()->username;

            $this->createInquiryDeleteLog($user, $inquiry);

            $inquiry->delete();

            DB::commit();

            return redirect()->route('inquiries')->with('success', 'Inquiry Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->createInquiryDeleteLog($user, $inquiry, 'Failed to delete Inquiry', $e->getMessage());

            return redirect()->route('inquiries')->with('failed', 'Failed to delete Inquiry!');
        }
    }

    private function createInquiryDeleteLog($user, $inquiry, $subject = 'Delete Inquiry Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Inquiry',
            'user' => $user,
            'subject' => $subject,
            'message' => "Inquiry from $inquiry->email has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }

        Log::create($logData);
    }
}
