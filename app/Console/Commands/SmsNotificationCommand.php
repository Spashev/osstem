<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Smsc\SmsService;

class SmsNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command start notification service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $to = Carbon::now()->addDays('3')->format('Y-m-d');
        $payments = Payment::with('contract')->where('payment_date', $to)->where('paid', '<', 'amount')->where('remain','<>', 0)->get();
        $result = [];
        if(count($payments) > 0) {
            foreach($payments as $payment) {
                $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг до %s.";
                $result = [
                    'customer_name' => $payment->contract->customer->name,
                    'customer_phone' => $payment->contract->customer->phone,
                    'amount' => $payment->amount,
                    'deadline' => Str::substr($payment->deadline, 0, 10)
                ];
                $text = sprintf($message, $result['customer_name'],$result['amount'], $result['deadline']);
                dump($text, $payment->deadline, $to);
                // $sms = new SmsService();
                // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
                // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                // $status = true;
                // if ($status) {
                //     $payment->notifications()->create([
                //         'payment_id' => $payment->id,
                //         'customer_name' => $result['customer_name'],
                //         'phone_number' => $result['customer_phone'],
                //         'amount' => $result['amount'],
                //         'status' => 0
                //     ]);
                // }
            }
        }

        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract', 'notifications')->where('deadline', '=', Carbon::now()->subDays(7)->format('Y-m-d'))->where('paid', '<', 'amount')->where('remain','<>', 0)->get();
        if(count($payments) > 0) {
            foreach($payments as $payment) {
                $contract_payments = Payment::where('contract_id', $payment->contract_id)->where('remain', '<>', 0)->get();
                $first_paymant_day = $contract_payments->first()->deadline;
                $delay = Carbon::createFromDate($first_paymant_day)->diffInDays($now);
                $amount = ((($payment->percent * $payment->amount) / 100) * $delay) + $payment->amount;
                $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг, просрочен сумма с процентом %s  дата %s.";
                $result = [
                    'customer_name' => $payment->contract->customer->name,
                    'customer_phone' => $payment->contract->customer->phone,
                    'amount' => $payment->amount,
                    'deadline' => Str::substr($payment->deadline, 0, 10),
                    'percent_amount' => $amount
                ];
                $text = sprintf($message, $result['customer_name'],$result['amount'], $result['percent_amount'], $now);
                dump($text, $delay, $first_paymant_day);
                // $sms = new SmsService();
                // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                // $status = true;
                // if($status) {
                //     $payment->notifications()->create([
                //         'payment_id' => $payment->id,
                //         'customer_name' => $result['customer_name'],
                //         'phone_number' => $result['customer_phone'],
                //         'amount' => $result['amount'],
                //         'status' => 1
                //     ]);
                //     $payment->save();
                // }
            }


            // foreach($payments as $payment) {
                //     // if(count($payment->notifications) > 0) {
                //         // foreach($payment->notifications as $key => $notify) {
                //             if($notify->status == 0) {
                //                 if($contract != $payment->contract->contract_no) {
                //                     $contract = $payment->contract->contract_no;
                //                     // $remainDays += Carbon::createFromDate($payment->deadline)->diffInDays(Carbon::now());
                //                     // dump($payment,$remainDays);
                //                 } 
                //                 dump($payment->notifications);
                //                 if(Carbon::now()->diffInDays($payment->notifications->last()->created_at) == 7 AND $count == $key + 1) {
                //                     $amount = ((($payment->percent * $payment->amount) / 100) * $remainDays) + $payment->amount;
                //                     $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг дата %s, просрочен сумма с процентом %s.";
                //                     $result = [
                //                         'customer_name' => $payment->contract->customer->name,
                //                         'customer_phone' => $payment->contract->customer->phone,
                //                         'amount' => $payment->amount,
                //                         'payment_date' => Str::substr($payment->payment_date, 0, 10),
                //                         'percent_amount' => $amount
                //                     ];
                //                     $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date'], $result['percent_amount']);
                //                     // dump($text, $remainDays);
                //                     $sms = new SmsService();
                //                     list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'Spashev');
                //                     list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                //                     $status = true;
                //                     if($status) {
                //                         $payment->notifications()->create([
                //                             'payment_id' => $payment->id,
                //                             'status' => 1
                //                         ]);
                //                         $payment->save();
                //                     }
                //                 }
                //             // }
                //         // }
                //     }
            // }
        }

        dump('finish');
        return 0;
    }
}