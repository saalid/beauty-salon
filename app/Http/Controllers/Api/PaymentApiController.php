<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\RedirectionForm;
use Illuminate\Http\Request;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;


class PaymentApiController extends Controller
{

    private $userId;

    public function __construct()
    {
//        $this->middleware('auth:api');
        $this->userId = 17;
    }

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
            function($driver, $transactionId) use($order){
                $transaction = new Transaction([
                    'user_id' => $this->userId,
                    'transaction_id' => $order->id,
                    'currency' => 'IRR',
                    'payment_method' => Order::class,
                    'amount' => $order->price,
                    'hash' => $transactionId,
                    'status' => 'pending',
                    'paid_at' => date('Y-m-d H:i:s')
                ]);
                $transaction->save();

                // Store transactionId in database.
                // We need the transactionId to verify payment in the future.
            }
        )->pay()->render();
    }

    /**
     * @param Request $request
     * @return array|true[]
     * @throws \Shetabit\Multipay\Exceptions\InvoiceNotFoundException
     */
    public function verify(Request $request)
    {
        try {
            $hashId = $request->get('Authority');

            $transaction = Transaction::where('hash', $hashId)->first();

            Payment::amount($transaction->amount)->transactionId($hashId)->verify();

            if ($transaction) {
                $transaction->update(['status' => 'paid']);
                Order::where('id', $transaction->transaction_id)
                    ->update([
                        'status' => 'completed'
                    ]);
            }

            return [
                'status' => true
            ];

            // Return success message
        } catch (InvalidPaymentException $exception) {
            $transaction = Transaction::where('hash', $exception->getTransactionId())->first();

            if ($transaction) {
                $transaction->update(['status' => 'failed']);
                $transaction->order->update(['status' => 'failed']);
            }

            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


}
