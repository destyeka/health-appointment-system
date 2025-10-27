<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointments = Appointment::whereDoesntHave('payment')->get(['id_appointment']);
        $methods = ['cash', 'credit_card', 'bank_transfer'];

        return view('payments.create', compact('appointments', 'methods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $expiredHours = (int) config('services.payment.expired_hours', 24);

        $payment = Payment::create([
            'id_appointment' => 1,
            'grand_total' => 150000,
            'booking_is_paid' => false,
            'repayment_is_paid' => false
        ]);

        $payment->paymentDetails()->create([
            'amount' => 150000,
            'method' => 'credit_card',
            'payment_type' => 'booking',
            'status_payment' => 'unpaid'
        ]);

        $detail = $payment->paymentDetails->first();
        $id = (string)$detail?->id_payment_detail;
        $amount = $detail?->amount;
        $payment_type = $detail?->payment_type;

        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->post(config('services.payment.base_url') . '/virtual-account/create', [
                        'external_id' => $id,
                        'amount' => $amount,
                        'customer_name' => auth()->user()->email,
                        'customer_email' => auth()->user()->email,
                        'customer_phone' => '081234567890',
                        'description' => 'Pembayaran ' . $payment_type,
                        'expired_duration' => $expiredHours,
                        'callback_url' => route('payments.index'),
                        'metadata' => [
                            'product_id' => $id,
                            'user_id' => auth()->id(),
                        ],
                    ]);

            if ($response->successful()) {
                $data = $response->json();

                $payment->paymentDetails()->where('payment_type', $payment_type)->update([
                    'status_payment' => 'paid'
                ]);

                if ($payment_type === 'booking') {
                    $payment->update([
                        'booking_is_paid' => true
                    ]);
                } else {
                    $payment->update([
                        'repayment_is_paid' => true
                    ]);
                }

                return redirect()->route('payments.index');

            } else {
                $payment->paymentDetails()->update(['status_payment' => 'unpaid']);
                dd($response->status(), $response->body(), $response->json());
                return redirect()->route('payments.index')
                    ->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
            }
            
        } catch (\Exception $e) {
            $payment->paymentDetails()->update(['status_payment' => 'unpaid']);
            return redirect()->route('payments.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $booking_amount = $payment->paymentDetails()
            ->where('payment_type', 'booking')->value('amount');
        $repayment_amount = $payment->paymentDetails()
            ->where('payment_type', 'repayment')->value('amount');
        $booking_method = $payment->paymentDetails()
            ->where('payment_type', 'booking')->value('method');
        $repayment_method = $payment->paymentDetails()
            ->where('payment_type', 'repayment')->value('method');
        return view('payments.show', compact('payment', 'booking_amount', 'repayment_amount', 'booking_method', 'repayment_method'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Deleted');
    }
}
