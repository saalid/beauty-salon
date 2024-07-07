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
        $hashId = $request->get('Authority');
        try {

            $transaction = Transaction::where('hash', $hashId)->first();

            Payment::amount($transaction->amount)->transactionId($hashId)->verify();

            if ($transaction) {
                $transaction->update(['status' => 'paid']);
                $order = Order::where('id', $transaction->transaction_id)->first();
                if($order){
                    $order->update([
                        'status' => 'completed'
                    ]);

                    $order->save();
                }

                event(new OrderVerified($order));
            }

            return [
                'status' => true
            ];

            // Return success message
        } catch (InvalidPaymentException $exception) {
            if($exception->getCode() !== 101)
            {
                $transaction = Transaction::where('hash', $hashId)->first();

                if ($transaction) {
                    $transaction->update(['status' => 'failed']);
                    Order::where('id', $transaction->transaction_id)
                        ->update([
                            'status' => 'failed'
                        ]);
                }
            }

            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


}
