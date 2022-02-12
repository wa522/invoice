<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\Section;
use App\Models\invoices_details;
use App\Models\invoices_attachments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = invoices::all();
        return view('Invoices.invoices' , compact( 'invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Add Invoice Page -- And Add Section Model (Relashtionshep)
        $select = Section::all();
        return view('Invoices.addInvoice' , compact('select'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save In Invoices Tabel In BD
        invoices::create([
            'invoice_number'        => $request->invoice_number,
            'invoice_Date'          => $request->invoice_Date,
            'Due_date'              => $request->Due_date,
            'section_id'            => $request->Section,
            'product'               => $request->product,
            'Amount_collection'     => $request->Amount_collection,
            'Amount_Commission'     => $request->Amount_Commission,
            'Discount'              => $request->Discount,
            'Rate_VAT'              => $request->Rate_VAT,
            'Value_VAT'             => $request->Value_VAT,
            'Total'                 => $request->Total,
            'note'                  => $request->note,
            'Status'                => 'غير مدفوعه',
            'Value_Status'          => 2,
        ]);
        // ID Invioce
        $invoice_id = invoices::latest()->first()->id;
        // Save To Invoices Details Tabel BD
        invoices_details::create([
            'id_Invoice'        => $invoice_id,
            'invoice_number'    => $request->invoice_number,
            'product'           => $request->product,
            'Section'           => $request->Section,
            'Status'            => 'غير مدفوعة',
            'Value_Status'      => 2,
            'note'              => $request->note,
            'user'              => (Auth::user()->name),
        ]);
        // Attachments Come At The Form (pic)
        if($request->hasFile('pic')) {
            // ID Invioce
            $invoice_id = invoices::latest()->first()->id;
            // File (pdf, jpeg ,.jpg , png)
            $image = $request->file('pic');
            // File Extention
            $imageName = $image->getClientOriginalName();
            // Invioce Numder
            $invoiceNumber = $request->invoice_number;

            // Save To Invoices Attachments Tabel BD
            $archive = new invoices_attachments();
            $archive -> file_name = $imageName;
            $archive -> invoice_number = $invoiceNumber;
            $archive -> Created_by = (Auth::user()->name);
            $archive -> invoice_id = $invoice_id;
            $archive -> save();

            // Save The File (pdf, jpeg ,.jpg , png) In Files Project In Folder (public/Attachments)
            $image_name = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoiceNumber), $image_name);
        }

        // Send Notification To Admin
            // The User Added New Invoice
        $user = User::get();
            // ID Invioce
        $invoice = invoices::latest()->first();
            // Go To Notification Page
        Notification::send($user, new \App\Notifications\Add_invoices($invoice));



        session()->flash('Add' , 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // View Payment Statuses

        // ID Form Payment Status And ID Invoice
        $invoices = invoices::where('id' , $id)->first();
        return view('Invoices.status_update', compact('invoices'));
    }

    public function Status_Update($id, Request $request)
    {
        // Update  Payment Statuses

        // ID Form Update Payment Status And ID Invoice
        $invoices = invoices::findOrFail($id);

        // Form Update To Driven (مدفوعة)
        if ($request->Status === 'مدفوعة') 
        {
            // Update Invoice -> Status == 1
            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            // Update Invoices Details
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        // Form Update To Partially Driven (مدفوعة جزئيا)
        else 
        {
            // Update Invoice -> Status == 3
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            // Update Invoices Details
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Edit Invoice With ID

        // ID Invoice
        $invoices = invoices::where('id',$id)->first();
        // Section Model (Relashtionshep)
        $sections = Section::all();
        // View Page Edit Invoices
        return view('Invoices.edit_invoice',compact('sections','invoices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Update Invoice After Edit

        // ID Invoice -> (invoice_id)
        $invoices = invoices::findOrFail($request -> invoice_id);
        // Update Invoice After Edit To BD
        $invoices -> update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Delete Invioce

        // ID Form  -> (invoice_id)
        $id = $request->invoice_id;
        // ID Form Delete And ID Invoice
        $invoices = invoices::where('id', $id)->first();
        // ID Form Delete And ID Attachments ->(invoice_id)
        $attachments = invoices_attachments::where('invoice_id', $id)->first();
        // ID Form Transfer
        $id_page =$request->id_page;

        // If ID Form Transfer Not= Value
        if (!$id_page==2)
        {
            // If Attachments Tabel Don't Empty
            if (!empty($attachments->invoice_number))
            {
                // Delete Attachments Folder
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }
            // Delete Invoice And Delete BD -> (forceDelete)
            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        }
        // ID Form Transfer == Value
        else 
        {
            // Delete Invoice And Don't Delete BD -> (SotDelete == Deleted At)
            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }

    // Get Products With ID Ajax Code
    public function getproducts($id) {
        $Ajax = DB::table('products')->where('section_id' , $id)->pluck('product_name' , 'id');
        return json_encode ($Ajax);
    }

    // Invoice Paid
    public function Invoice_Paid()
    {
        // Value Status In Invoice == 1 (Paid)
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('Invoices.invoices_paid',compact('invoices'));
    }

    // Invoice UnPaid
    public function Invoice_unPaid()
    {
        // Value Status In Invoice == 2 (UnPaid)
        $invoices = Invoices::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    // Invoice Partial
    public function Invoice_Partial()
    {
        // Value Status In Invoice == 3 (Partial)
        $invoices = Invoices::where('Value_Status',3)->get();
        return view('invoices.invoices_Partial',compact('invoices'));
    }

     // Print Invoice
    public function Print_invoice ($id)
    {
        // ID Form Print And ID Invoice
        $invoices = invoices::where('id' , $id)->first();
        return view('Invoices.Print_invoice' , compact('invoices'));
    }

    // Set to read all
    public function MarkAsRead_all (Request $request)
    {
        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }

    public function unreadNotifications_count()
    {
        return auth()->user()->unreadNotifications->count();
    }

    public function unreadNotifications()
    {
        foreach (auth()->user()->unreadNotifications as $notification){
        return $notification->data['title'];
        }
    }
}
