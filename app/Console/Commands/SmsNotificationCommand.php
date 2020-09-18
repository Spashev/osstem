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
        $payments = Payment::with('contract')->where('payment_date', $to)->where('paid', 0)->where('remain','<>', 0)->get();
        $result = [];
        if(count($payments) > 0) {
            foreach($payments as $payment) {
                if(count($payment->notifications) > 0) {
                    // dd($payment->notifications->last());
                    $notify = $payment->notifications->last();
                    // foreach($payment->notifications as $notify) {
                        if($notify->created_at->format('Y-m-d') == Carbon::now()->subMonth()->format('Y-m-d')) {
                            dump('SMS month');
                            $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг до %s.";
                            $result = [
                                'customer_name' => $payment->contract->customer->name,
                                'customer_phone' => $payment->contract->customer->phone,
                                'amount' => $payment->amount,
                                'payment_date' => Str::substr($payment->payment_date, 0, 10)
                            ];
                            $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date']);
                            dump($text);
                            // $sms = new SmsService();
                            // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
                            // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                            $status = true;
                            if ($status) {
                                $payment->notifications()->create([
                                    'payment_id' => $payment->id,
                                    'status' => 0
                                ]);
                            }
                        // }
                    }
                } else {
                    dump("sms 3");
                    $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг до %s.";
                    $result = [
                        'customer_name' => $payment->contract->customer->name,
                        'customer_phone' => $payment->contract->customer->phone,
                        'amount' => $payment->amount,
                        'payment_date' => Str::substr($payment->payment_date, 0, 10)
                    ];
                    $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date']);
                    dump($text);
                    // $sms = new SmsService();
                    // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
                    // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                    $status = true;
                    if ($status) {
                        $payment->notifications()->create([
                            'payment_id' => $payment->id,
                            'status' => 0
                        ]);
                    }
                }
            }
        }

        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract', 'notifications')->where('deadline', '<', $now)->where('paid', 0)->where('remain','<>', 0)->get();
        if(count($payments) > 0) {
            $contract = '';
            foreach($payments as $payment) {
                if(count($payment->notifications) > 0) {
                    $minusDay = 0;
                    $remainDays = 0;
                    foreach($payment->notifications as $notify) {
                        if($notify->status == 0) {
                            if($contract != $payment->contract->contract_no) {
                                $contract = $payment->contract->contract_no;
                                $remainDays += Carbon::createFromDate($payment->deadline)->diffInDays(Carbon::now());
                                dump($payment,$remainDays);
                            } 
                            if(Carbon::now()->diffInDays($notify->created_at) == 7) {
                                $amount = ((($payment->percent * $payment->amount) / 100) * $remainDays) + $payment->amount;
                                $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг дата %s, просрочен сумма с процентом %s.";
                                $result = [
                                    'customer_name' => $payment->contract->customer->name,
                                    'customer_phone' => $payment->contract->customer->phone,
                                    'amount' => $payment->amount,
                                    'payment_date' => Str::substr($payment->payment_date, 0, 10),
                                    'percent_amount' => $amount
                                ];
                                $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date'], $result['percent_amount']);
                                dump($text, $remainDays);
                                // $sms = new SmsService();
                                // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'Spashev');
                                // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                                $status = true;
                                if($status) {
                                    $notify->status = 1;
                                    $notify->save();
                                    $payment->notifications()->create([
                                        'payment_id' => $payment->id,
                                        'status' => 1
                                    ]);
                                    $payment->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        dump('finish');
        return 0;
    }
}