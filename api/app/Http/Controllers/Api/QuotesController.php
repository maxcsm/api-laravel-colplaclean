<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Notification;
use App\Models\Quotes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Mail\Form1Mail;
use App\Mail\Form2Mail;
use PDF;

class QuotesController extends Controller
{
        /**
         *
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request)
        {
            $page = $request->page;
            $per_page= $request->per_page;
            $order_id= $request->order_id;
            $filter= $request->filter;
            $order_by = $request->order_by;
            $category = $request->category;
    
            if (empty($filter)) {
            return Quotes::groupBy('InvoiceID')
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
            }
    
            if (!empty($filter)) {
            return  Quotes::groupBy('InvoiceID')
            ->where('InvoiceNumber', 'LIKE', "%{$filter}%")
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
            }
        }
    
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create(Request $request)
        {
            //
        }
    
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $input = $request->all();
            $invoices= DB::table('quotes')
            ->select('InvoiceID')
            ->orderBy('InvoiceID', 'asc')
            ->get()
            ->last();
           
            $input['InvoiceID']=$invoices->InvoiceID+1;
            //$DueDate= Carbon::now()->add(15, 'day')
            $DueDate= Carbon::now();
            $input['DueDate']=$DueDate;
            $invoice = Quotes::create($input);
            return response()->json($invoice, 201);
        }
    
        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            return response()->json(Quotes::find($id), 200);
        }
    
        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            //
        }
    
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update($id, Request $request)
        {
            $user = Quotes::findOrFail($id);
            $user->update($request->all());
            return response()->json($user, 200);
    
        }
    
        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            Invoices::findOrFail($id)->delete();
            return response()->json('Deleted Successfully', 200);
        }
    

          public function userByrole(Request $request)
        {
            $page = $request->page;
            $per_page= $request->per_page;
            $order_id= $request->order_id;
            $filter= $request->filter;
            $order_by = $request->order_by;
    
            return User::where('role', 'LIKE', "%{$filter}%")
                ->orderBy($order_id, $order_by)
                ->paginate($per_page);
        }
    
    
        public function invoiceById($id, Request $request)
        {
            $page = $request->page;
            $per_page = 100;
            $order_by = 'asc';
            $order_id = 'id';
    
            return Quotes::where('InvoiceID', $id)
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
        }
    
    public function invoiceview($id,Request $request)
    {

        $idinvoice=$id;
        $invoicesrow = DB::table('quotes')
        ->where('InvoiceID',$idinvoice)
        ->get();
   
        $ItemTax1=$invoicesrow[0]->ItemTax1;
        $userid=$invoicesrow[0]->CustomerID;
        $usersrow  = DB::table('users')
        ->where('id','=',$userid)
        ->select('users.*')
        ->get();

        
        $company=$usersrow[0]->company;
        $firstname=$usersrow[0]->firstname;
        $lastname=$usersrow[0]->lastname;
        $shipping_address=$usersrow[0]->shipping_address;
        $shipping_cp=$usersrow[0]->shipping_cp;
        $shipping_city=$usersrow[0]->shipping_city;
        $shipping_phone=$usersrow[0]-> shipping_phone;
        $billing_phone=$usersrow[0]->billing_phone;
        $tva_number=$usersrow[0]->tva_number;
        $siret_number=$usersrow[0]->siret_number;
        $due_date=$invoicesrow[0]->DueDate;
        $due_date = Carbon::createFromFormat('Y-m-d',$due_date)->format('d/m/Y');
        $client = new Party([
        ]);

        $customer = new Party([
            'company'          => $company,
            'firstname'          => $firstname,
            'lastname'          => $lastname,
            'shipping_address'       =>  $shipping_address,
            'shipping_cp'          => $shipping_cp ,
            'shipping_city'          => $shipping_city ,
            'shipping_phone'          => $shipping_phone ,
            'billing_phone'          => $billing_phone ,
            'tva_number'          => $tva_number ,
            'siret_number'          => $siret_number,
            'due_date'          => $due_date 
        ]);
        $items = [];
        foreach (  $invoicesrow as $invoiceitem ) {
             array_push( $items,  (new InvoiceItem())
             ->title($invoiceitem->ItemName)
             ->description($invoiceitem->ItemDesc)
             ->pricePerUnit($invoiceitem->ItemTotal)
             ->quantity($invoiceitem->Quantity)
             );
        }
        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);
        $path = public_path('invoices');
        $date = Carbon::now()->timezone('Europe/Stockholm')->toDateTimeString();
        $randomtitle=random_int(100000,999999);
        $fileName =  $randomtitle . '.' . 'pdf' ;


        $invoice = Invoice::make('receipt')
            ->series('Devis')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence($idinvoice)
            ->serialNumberFormat('{SEQUENCE}')
            ->seller($client)
            ->buyer($customer)
            ->date(now())
            ->dateFormat('d/m/Y')
           // ->payUntilDays($invoicesrow[0]->DueDate)
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->taxRate($ItemTax1)
            ->template('quote')
            ->logo(public_path('images/logo-invoice.png'))
            // You can additionally save generated invoice to configured disk
       //     ->save('public/invoices');

       ->save('public');
    
       $link = $invoice->url();
       // Then send email to party with link
       // And return invoice itself to browser or have a different view
       return $invoice->stream();
        // Then send email to party with link
    }


    public function quotesend($id,Request $request)
    {

        
        $idinvoice=$id;
        $invoicesrow = DB::table('quotes')
        ->where('InvoiceID',$idinvoice)
        ->get();
   
        $ItemTax1=$invoicesrow[0]->ItemTax1;
        $userid=$invoicesrow[0]->CustomerID;
        $usersrow  = DB::table('users')
        ->where('id','=',$userid)
        ->select('users.*')
        ->get();

        
        $company=$usersrow[0]->company;
        $firstname=$usersrow[0]->firstname;
        $lastname=$usersrow[0]->lastname;
        $shipping_address=$usersrow[0]->shipping_address;
        $shipping_cp=$usersrow[0]->shipping_cp;
        $shipping_city=$usersrow[0]->shipping_city;
        $shipping_phone=$usersrow[0]-> shipping_phone;
        $billing_phone=$usersrow[0]->billing_phone;
        $tva_number=$usersrow[0]->tva_number;
        $siret_number=$usersrow[0]->siret_number;
        $due_date=$invoicesrow[0]->DueDate;
        $due_date = Carbon::createFromFormat('Y-m-d',$due_date)->format('d/m/Y');

        $email=$usersrow[0]->email;


        $client = new Party([
        ]);

        $customer = new Party([
            'company'          => $company,
            'firstname'          => $firstname,
            'lastname'          => $lastname,
            'shipping_address'       =>  $shipping_address,
            'shipping_cp'          => $shipping_cp ,
            'shipping_city'          => $shipping_city ,
            'shipping_phone'          => $shipping_phone ,
            'billing_phone'          => $billing_phone ,
            'tva_number'          => $tva_number ,
            'siret_number'          => $siret_number,
            'due_date'          => $due_date 
        ]);
        $items = [];
        foreach (  $invoicesrow as $invoiceitem ) {
             array_push( $items,  (new InvoiceItem())
             ->title($invoiceitem->ItemName)
             ->description($invoiceitem->ItemDesc)
             ->pricePerUnit($invoiceitem->ItemTotal)
             ->quantity($invoiceitem->Quantity)
             );
        }
        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);
        $path = public_path('invoices');
        $date = Carbon::now()->timezone('Europe/Stockholm')->toDateString();
        $randomtitle=random_int(100000,999999);
        $fileName =$date .'.'. $randomtitle . '.' . 'pdf' ;


        $invoice = Invoice::make('receipt')
            ->series('Devis')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence($idinvoice)
            ->serialNumberFormat('{SEQUENCE}')
            ->seller($client)
            ->buyer($customer)
            ->date(now())
            ->dateFormat('d/m/Y')
           // ->payUntilDays(14)
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->taxRate($ItemTax1)
            ->logo(public_path('images/logo-invoice.png'))
            ->template('quote')
            ->save('public');



         $path = public_path('pdf/');
         $pdf = PDF::loadView('pdf.quote', ['invoice' => $invoice])->setPaper('a4', 'portrait');
         $pdf->save($path . '/' . $fileName);

       
         $user = ['email' => "",'password' => "",];
         Mail::to($email)->send(new Form2Mail($pdf));

    
     /*
        $notif = Notification::create([
             'title' => "PDF",
             'content' => "Pdf à télécharger",
             'edited_by' => $request->id,
             'link' => 'pdf/'.$fileName,
             'category' => "PDF",
             'view'=>0
         ]);

         */
     
        return response()->json([
         'message' => 'Successfully created',$fileName
     ]);
    }


    public function updateInvoiceId($id, Request $request)
    {
       

      $invoice = Quotes::findOrFail($id);
      $input=$request->all();
      //$price=$request->ItemPrice;
    //  $qte=$request->Quantity;
      
      $invoice->update($input);

      return response()->json([ 'invoice'=>$invoice ],200);
    
    }

    public function updateAllprice( Request $request)
    {
       
      $InvoiceID=$request->InvoiceID;
      $ItemTax1=$request->ItemTax1;
      $DueDate=$request->DueDate;
      $SubTotal=0;
      $invoicesrow = DB::table('quotes')->where('InvoiceID',$InvoiceID)->get();
     
      foreach ($invoicesrow as $items) {
        $price = $items->ItemTotal ;
        $qty = $items->Quantity; 
        $SubTotal =  $SubTotal + ($price*$qty);
      }

      $SubTotal  = $SubTotal;
      $ItemTax1Amount  = ($SubTotal * $ItemTax1)/100;
      $Total = $SubTotal + $ItemTax1Amount;

      foreach ($invoicesrow as $items) {
        $invo['ItemTax1']= $ItemTax1;
        $invo['SubTotal']= $SubTotal;
        $invo['ItemTax1Amount']= $ItemTax1Amount;
        $invo['Total']= $Total;
        $invo['DueDate']= $DueDate;
        $id = $items->id;
        $invoice = Quotes::findOrFail($id);
        $invoice->update($invo);
      }


      return response()->json([ 'subTotal'=>$SubTotal,'invoice'=>$invoicesrow ],200);
    
    }



    public function Allinvoicesclose()
    {
     $invoices= DB::table('quotes')
      ->select('InvoiceID')
      ->orderBy('InvoiceID', 'asc')
      ->get()
    ->last();
    return response()->json([ 'invoices'=>$invoices ],200);  
    }

    public function quotesByUser($id, Request $request)
    {
      $invoicesbyuser = DB::table('quotes')
       ->where('quotes.CustomerID',$id)
       ->groupBy('InvoiceID')
    //   ->orderBy("start_at", "desc")
       ->get();
       return response()->json($invoicesbyuser, 200);

    }


    public function addItemInvoice(Request $request)
    {
        $input = $request->all();
        $invoice = Quotes::create($input);
        return response()->json($invoice, 201);
    }
}



