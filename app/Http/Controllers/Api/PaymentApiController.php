<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderVerified;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Illuminate\Http\Request;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;


class PaymentApiController extends Controller
{


    /**
     * @param Order $order
     * @return mixed
     * @throws \Exception
     */
    public function purchase(Order $order)
    {

        $invoice = new Invoice;
        $invoice->amount($order->price);

        return Payment::purchase(
            (new Invoice)->amount($order->price),
            function ($driver, $transactionId) use ($order) {
                Transaction::updateOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'transaction_id' => $order->id,
                    ],
                    [
                        'currency' => 'IRR',
                        'payment_method' => Order::class,
                        'amount' => $order->price,
                        'hash' => $transactionId,
                        'status' => 'pending',
                        'paid_at' => date('Y-m-d H:i:s')
                    ]
                );

                // Store transactionId in database.
                // We need the transactionId to verify payment in the future.
            }
        )->pay()->render();
    }

    /**
     * @param Request $request
     * @throws \Shetabit\Multipay\Exceptions\InvoiceNotFoundException
     */
    public function verify(Request $request)
    {
        $hashId = $request->get('Authority');
        try {

            $transaction = Transaction::where('hash', $hashId)->first();

            Payment::amount($transaction->amount)->transactionId($hashId)->verify();

            if ($transaction) {
                $transaction->update(['status' => 'paid']);
                $order = Order::where('id', $transaction->transaction_id)->first();
                if ($order) {
                    $order->update([
                        'status' => 'completed'
                    ]);

                    $order->save();
                }

                event(new OrderVerified($order));
            }

            return redirect('http://neginzare.com/purchase/success');

            // Return success message
        } catch (InvalidPaymentException $exception) {
            if ($exception->getCode() !== 101) {
                $transaction = Transaction::where('hash', $hashId)->first();

                if ($transaction) {
                    $transaction->update(['status' => 'failed']);
                    Order::where('id', $transaction->transaction_id)
                        ->update([
                            'status' => 'failed'
                        ]);
                }
            }

            return redirect('http://neginzare.com/purchase/failed');
        }
    }


}
