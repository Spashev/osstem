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
        
        dump($payments);
        $result = [];
        foreach($payments as $payment) {
            if(count($payment->notifications) > 0) {
                foreach($payment->notifications as $notify) {
                    if($notify->created_at->format('Y-m-d') == Carbon::now()->subDays(10)->format('Y-m-d')) {
                        $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг дата %s просрочен сумма с процентом %s.";
                        $result = [
                            'customer_name' => $payment->contract->customer->name,
                            'customer_phone' => $payment->contract->customer->phone,
                            'amount' => $payment->amount,
                            'payment_date' => Str::substr($payment->payment_date, 0, 10),
                            'percent_amount' => $payment->percent_amount
                        ];
                        $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date'], $result['percent_amount']);
                        $sms = new SmsService();
                        list($sms_id, $sms_cnt, $cost, $balance) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'Spashev');
                        list($status, $time) = $sms->get_status($sms_id, $result['customer_phone']);
                        if($status) {
                            $payment->notifications()->create([
                                'payment_id' => $payment->id,
                                'status' => 1
                            ]);
                        }
                    }
                    sleep(1);
                }
            } else {
                $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг до %s.";
                $result = [
                    'customer_name' => $payment->contract->customer->name,
                    'customer_phone' => $payment->contract->customer->phone,
                    'amount' => $payment->amount,
                    'payment_date' => Str::substr($payment->payment_date, 0, 10)
                ];
                $text = sprintf($message, $result['customer_name'],$result['amount'], $result['payment_date']);
                $sms = new SmsService();
                list($sms_id, $sms_cnt, $cost, $balance) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
                list($status, $time) = $sms->get_status($sms_id, $result['customer_phone']);
                if ($status) {
                    $payment->notifications()->create([
                        'payment_id' => $payment->id,
                        'status' => 1
                    ]);
                }
            }
            sleep(1);
        }
        return 0;
    }
}